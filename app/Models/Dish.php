<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Dish extends Model {
    protected $table = 'menu_items';
    protected $primaryKey = 'item_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'name',
        'description',
        'price',
        'image_url',
        'is_available'
    ];

    public function ingredients() {
        return $this->belongsToMany(Ingredient::class, 'menu_item_ingredients', 'item_id', 'sku_code')
                    ->withPivot('quantity_required');
    }

    public static function getAll() {
        $items = self::with('ingredients')
                     ->where('is_available', true)
                     ->orderBy('created_at', 'desc')
                     ->get();
        
        $result = $items->toArray();
        foreach ($result as &$item) {
            $item['id'] = $item['item_id'];
            $item['image'] = $item['image_url'];
        }
        return $result;
    }

    public static function saveWithIngredients($dishData, $ingredientData) {
        return DB::transaction(function() use ($dishData, $ingredientData) {
            $itemId = $dishData['item_id'] ?? 'm_' . bin2hex(random_bytes(4));

            $existingDish = self::where('name', $dishData['name'])->first();
            if ($existingDish) {
                if ((bool)$existingDish->is_available) {
                    throw new \Exception("Ya existe un plato activo con ese nombre.");
                }

                $existingDish->update([
                    'description' => $dishData['description'],
                    'price' => $dishData['price'],
                    'image_url' => $dishData['image'] ?? $dishData['image_url'] ?? null,
                    'is_available' => true,
                ]);

                if (!empty($ingredientData)) {
                    $syncData = [];
                    foreach ($ingredientData as $sku => $qty) {
                        $syncData[$sku] = ['quantity_required' => $qty];
                    }
                    $existingDish->ingredients()->sync($syncData);
                }

                return $existingDish;
            }
            
            $dish = self::create([
                'item_id' => $itemId,
                'name' => $dishData['name'],
                'description' => $dishData['description'],
                'price' => $dishData['price'],
                'image_url' => $dishData['image'] ?? $dishData['image_url'] ?? null,
                'is_available' => $dishData['is_available'] ?? true
            ]);

            if (!empty($ingredientData)) {
                foreach ($ingredientData as $sku => $qty) {
                    $dish->ingredients()->attach($sku, ['quantity_required' => $qty]);
                }
            }

            return $dish;
        });
    }

    public static function add($data) {
        return self::saveWithIngredients($data, $data['ingredients'] ?? []);
    }

    public static function updateDish($id, $data) {
        $dish = self::where('item_id', $id)->first();
        if (!$dish) return false;

        $changingSensitiveData = ($dish->name !== $data['name'] || (float)$dish->price !== (float)$data['price']);

        if ($changingSensitiveData && self::isUsedInActiveOrder($id)) {
            throw new \Exception("Regla de Negocio: No se puede editar el nombre o precio de un plato con pedidos activos.");
        }

        return DB::transaction(function() use ($dish, $data) {
            $dish->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'image_url' => $data['image'] ?? $data['image_url'] ?? null,
                'is_available' => $data['is_available'] ?? true
            ]);

            if (isset($data['ingredients'])) {
                $syncData = [];
                foreach ($data['ingredients'] as $sku => $qty) {
                    $syncData[$sku] = ['quantity_required' => $qty];
                }
                $dish->ingredients()->sync($syncData);
            }

            return true;
        });
    }

    public static function deleteDish($id) {
        if (self::isUsedInActiveOrder($id)) {
            throw new \Exception("No se puede eliminar un plato que tiene pedidos activos.");
        }

        $dish = self::where('item_id', $id)->first();
        if (!$dish) {
            throw new \Exception("Plato no encontrado.");
        }

        return $dish->update(['is_available' => false]);
    }

    public static function findDish($id) {
        $dish = self::where('item_id', $id)->first();
        if ($dish) {
            $data = $dish->toArray();
            $data['id'] = $data['item_id'];
            $data['image'] = $data['image_url'];
            return $data;
        }
        return null;
    }

    private static function isUsedInActiveOrder($id) {
        return OrderDetail::where('item_id', $id)
            ->whereHas('order', function($query) {
                $query->whereNotIn('status', ['Completado', 'Cancelado', 'Confirmed']);
            })->count() > 0;
    }
}
