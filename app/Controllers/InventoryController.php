<?php
namespace App\Controllers;

use App\Models\Ingredient;
use App\Models\InventoryBatch;
use Illuminate\Database\Capsule\Manager as DB;

class InventoryController {
    
    public function index() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'administrator'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $inventory = $this->getInventoryStatus();
        $ingredients = Ingredient::all();

        require_once __DIR__ . '/../Views/admin/inventory.php';
    }

    public function getInventoryStatus() {
        return Ingredient::with(['inventoryBatches' => function($query) {
            $query->where('quantity', '!=', 0); // Consideramos lotes con stock positivo o ajustes
        }])->get()->map(function($ingredient) {
            return [
                'sku_code' => $ingredient->sku_code,
                'name' => $ingredient->name,
                'unit' => $ingredient->unit_of_measurement,
                'total_stock' => (float)$ingredient->inventoryBatches->sum('quantity')
            ];
        });
    }

    public function storeBatch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                DB::transaction(function() {
                    $sku = $_POST['sku_code'] ?? null;
                    $name = $_POST['name'];
                    $unit = $_POST['unit_of_measurement'];

                    $ingredient = null;
                    if ($sku) {
                        $ingredient = Ingredient::find($sku);
                    }

                    if ($ingredient) {
                        $ingredient->update([
                            'name' => $name,
                            'unit_of_measurement' => $unit
                        ]);
                    } else {
                        $ingredient = Ingredient::where('name', $name)->first();
                        if (!$ingredient) {
                            $sku = 'ING-' . strtoupper(bin2hex(random_bytes(3)));
                            $ingredient = Ingredient::create([
                                'sku_code' => $sku,
                                'name' => $name,
                                'unit_of_measurement' => $unit
                            ]);
                        }
                    }

                    InventoryBatch::create([
                        'batch_id' => 'batch_' . bin2hex(random_bytes(4)),
                        'sku_code' => $ingredient->sku_code,
                        'quantity' => (float)$_POST['quantity'],
                        'cost_per_unit' => (float)$_POST['cost_per_unit'],
                        'purchase_date' => $_POST['purchase_date'] ?: date('Y-m-d')
                    ]);
                });
                echo "<script>alert('Lote e ingrediente procesados correctamente.'); window.location.href='index.php?action=inventory';</script>";
            } catch (\Exception $e) {
                echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='index.php?action=inventory';</script>";
            }
            exit();
        }
    }

    /**
     * Actualiza ingrediente y realiza ajuste de stock mediante un nuevo lote.
     */
    public function editIngredient() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                DB::transaction(function() {
                    $sku = $_POST['sku_code'];
                    $ingredient = Ingredient::findOrFail($sku);
                    
                    // 1. Actualizar metadatos básicos
                    $ingredient->update([
                        'name' => $_POST['name'],
                        'unit_of_measurement' => $_POST['unit_of_measurement']
                    ]);

                    // 2. Lógica de Ajuste de Stock
                    $currentStock = (float)$ingredient->inventoryBatches()->sum('quantity');
                    $newStock = (float)$_POST['total_stock'];
                    $adjustment = $newStock - $currentStock;

                    if (abs($adjustment) > 0.0001) { // Si hay un cambio significativo
                        InventoryBatch::create([
                            'batch_id' => 'adj_' . bin2hex(random_bytes(4)),
                            'sku_code' => $sku,
                            'quantity' => $adjustment,
                            'cost_per_unit' => 0, // Ajuste manual no tiene costo de compra
                            'purchase_date' => date('Y-m-d'),
                        ]);
                    }
                });
                echo "<script>alert('Ingrediente y stock actualizados correctamente.'); window.location.href='index.php?action=inventory';</script>";
            } catch (\Exception $e) {
                echo "<script>alert('Error al actualizar: " . $e->getMessage() . "'); window.location.href='index.php?action=inventory';</script>";
            }
            exit();
        }
    }

    public function deleteIngredient() {
        if (isset($_GET['sku'])) {
            try {
                DB::transaction(function() {
                    $sku = $_GET['sku'];
                    $ingredient = Ingredient::findOrFail($sku);
                    
                    // Verificamos si está siendo usado en algún plato
                    if ($ingredient->menuItems()->count() > 0) {
                        throw new \Exception("No se puede eliminar un ingrediente vinculado a un plato activo.");
                    }

                    $ingredient->delete(); 
                });
                header('Location: index.php?action=inventory');
            } catch (\Exception $e) {
                echo "<script>alert('Error al eliminar: " . $e->getMessage() . "'); window.location.href='index.php?action=inventory';</script>";
            }
            exit();
        }
    }
}
