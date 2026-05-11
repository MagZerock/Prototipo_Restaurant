<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryBatch extends Model {
    protected $table = 'inventory_batches';
    protected $primaryKey = 'batch_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'batch_id',
        'sku_code',
        'quantity',
        'cost_per_unit',
        'purchase_date'
    ];

    public static function getByIngredient($skuCode) {
        return self::where('sku_code', $skuCode)->orderBy('purchase_date', 'asc')->get()->toArray();
    }

    public static function add($data) {
        return self::create([
            'batch_id' => 'batch_' . bin2hex(random_bytes(4)),
            'sku_code' => $data['sku_code'],
            'quantity' => $data['quantity'],
            'cost_per_unit' => $data['cost_per_unit'],
            'purchase_date' => $data['purchase_date'] ?? date('Y-m-d')
        ]);
    }
}
