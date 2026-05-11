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
        $ingredients = DB::table('ingredients')->get();
        return $ingredients->map(function($ingredient) {
            $stock = (float)$ingredient->stock_disponible;
            if ($stock <= 0) {
                $estado = 'AGOTADO';
                $estadoClass = 'bg-red-50 text-red-500 border-red-100';
            } elseif ($stock < 10) {
                $estado = 'STOCK BAJO';
                $estadoClass = 'bg-yellow-50 text-yellow-600 border-yellow-100';
            } else {
                $estado = 'ÓPTIMO';
                $estadoClass = 'bg-green-50 text-green-600 border-green-100';
            }
            return [
                'sku_code'   => $ingredient->sku_code,
                'name'       => $ingredient->name,
                'unit'       => $ingredient->unit_of_measurement,
                'total_stock'=> $stock,
                'estado'     => $estado,
                'estadoClass'=> $estadoClass,
            ];
        });
    }

    private function syncStock(string $sku) {
        $total = DB::table('inventory_batches')
            ->where('sku_code', $sku)
            ->sum('quantity');
        DB::table('ingredients')
            ->where('sku_code', $sku)
            ->update(['stock_disponible' => (float)$total]);
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
                    $this->syncStock($ingredient->sku_code);
                });
                echo "<script>alert('Lote e ingrediente procesados correctamente.'); window.location.href='index.php?action=inventory';</script>";
            } catch (\Exception $e) {
                echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='index.php?action=inventory';</script>";
            }
            exit();
        }
    }

    public function editIngredient() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                DB::transaction(function() {
                    $sku = $_POST['sku_code'];
                    $ingredient = Ingredient::findOrFail($sku);
                    
                    $ingredient->update([
                        'name' => $_POST['name'],
                        'unit_of_measurement' => $_POST['unit_of_measurement']
                    ]);

                    $currentStock = (float)$ingredient->inventoryBatches()->sum('quantity');
                    $newStock = (float)$_POST['total_stock'];
                    $adjustment = $newStock - $currentStock;

                    if (abs($adjustment) > 0.0001) { 
                        InventoryBatch::create([
                            'batch_id' => 'adj_' . bin2hex(random_bytes(4)),
                            'sku_code' => $sku,
                            'quantity' => $adjustment,
                            'cost_per_unit' => 0,
                            'purchase_date' => date('Y-m-d'),
                        ]);
                    }
                    $this->syncStock($sku);
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
