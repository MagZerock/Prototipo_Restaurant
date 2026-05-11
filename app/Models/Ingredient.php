<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model {
    protected $table = 'ingredients';
    protected $primaryKey = 'sku_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'sku_code',
        'name',
        'unit_of_measurement'
    ];

    public function menuItems() {
        return $this->belongsToMany(Dish::class, 'menu_item_ingredients', 'sku_code', 'item_id')
                    ->withPivot('quantity_required');
    }

    public function inventoryBatches() {
        return $this->hasMany(InventoryBatch::class, 'sku_code', 'sku_code');
    }
    
    public function getLastCost() {
        $lastBatch = $this->inventoryBatches()->orderBy('purchase_date', 'desc')->first();
        return $lastBatch ? (float)$lastBatch->cost_per_unit : 0.0;
    }

    public static function getAll() {
        return self::all()->toArray();
    }
}
