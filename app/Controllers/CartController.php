<?php
namespace App\Controllers;

use App\Models\Dish;
use App\Models\OrderDetail;

class CartController {
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        require_once __DIR__ . '/../Views/cart.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'];
                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
                
                $dish = Dish::where('item_id', $id)->first();

                if ($dish) {
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }

                    $newQuantity = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['quantity'] + $quantity : $quantity;

                    $tempDetail = new OrderDetail([
                        'item_id' => $dish->item_id,
                        'quantity' => $newQuantity,
                        'selling_price' => (float)$dish->price
                    ]);
                    
                    $tempDetail->setRelation('menuItem', $dish);

                    $_SESSION['cart'][$id] = [
                        'item_id' => $dish->item_id,
                        'name' => $dish->name,
                        'quantity' => $newQuantity,
                        'price' => (float)$dish->price, 
                        'selling_price' => (float)$dish->price,
                        'ingredient_cost' => $tempDetail->calculateItemCost(),
                        'ingredients' => $dish->ingredients->pluck('name')->toArray(),
                        'image_url' => $dish->image_url
                    ];
                }
            } catch (\Exception $e) {
                echo "<script>alert('Error en la base de datos: " . $e->getMessage() . "'); window.location.href='index.php?action=menu';</script>";
                exit();
            }
        }
        header('Location: index.php?action=menu');
        exit();
    }

    public function remove() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
        }
        header('Location: index.php?action=cart');
        exit();
    }
}
