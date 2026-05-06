<?php
namespace App\Controllers;

use App\Models\Dish;

class CartController {
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        require_once __DIR__ . '/../Views/cart.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            $dishes = Dish::getAll();
            $dish = null;
            foreach ($dishes as $d) {
                if ((string)$d['id'] === (string)$id) {
                    $dish = $d;
                    break;
                }
            }

            if ($dish) {
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$id] = [
                        'name' => $dish['name'],
                        'price' => $dish['price'],
                        'quantity' => $quantity,
                        'ingredients' => $_POST['ingredients'] ?? $dish['ingredients']
                    ];
                }
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
