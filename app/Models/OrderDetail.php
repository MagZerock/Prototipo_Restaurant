<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model {
    protected $table = 'order_details';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'selling_price',
        'ingredient_cost',
        'removed_ingredients'
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function menuItem() {
        return $this->belongsTo(Dish::class, 'item_id', 'item_id');
    }

    public function calculateItemCost() {
        $dish = $this->menuItem;
        if (!$dish) return 0.0;
        
        $totalIngredientCost = 0.0;
        
        foreach ($dish->ingredients as $ingredient) {
            $totalIngredientCost += $ingredient->getLastCost();
        }
        
        return $totalIngredientCost * $this->quantity;
    }
}
