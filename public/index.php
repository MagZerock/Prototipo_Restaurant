<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../config/connection.php';

use App\Controllers\HomeController;
use App\Controllers\MenuController;
use App\Controllers\CartController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {
    case 'home':
        (new HomeController())->index();
        break;
    case 'menu':
        (new MenuController())->index();
        break;
    case 'about':
        (new HomeController())->about();
        break;
    case 'locations':
        (new HomeController())->locations();
        break;
    case 'reservations':
        (new HomeController())->reservations();
        break;
    case 'survey':
        (new HomeController())->survey();
        break;
    case 'cart':
        (new CartController())->index();
        break;
    case 'add_to_cart':
        (new CartController())->add();
        break;
    case 'remove_from_cart':
        (new CartController())->remove();
        break;
    case 'login':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->register();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'admin_dashboard':
        (new AdminController())->dashboard();
        break;
    case 'add_dish':
        (new AdminController())->addDish();
        break;
    case 'edit_dish':
        (new AdminController())->editDish();
        break;
    case 'delete_dish':
        (new AdminController())->deleteDish();
        break;
    case 'update_order_status':
        (new AdminController())->updateOrderStatus();
        break;
    case 'admin_reservations':
        (new AdminController())->reservations();
        break;
    case 'admin_surveys':
        (new AdminController())->surveys();
        break;
    case 'update_reservation_status':
        (new AdminController())->updateReservationStatus();
        break;
    case 'cancel_reservation':
        (new HomeController())->cancelReservation();
        break;
    case 'inventory':
        (new \App\Controllers\InventoryController())->index();
        break;
    case 'store_supply':
        (new \App\Controllers\InventoryController())->storeBatch();
        break;
    case 'edit_ingredient':
        (new \App\Controllers\InventoryController())->editIngredient();
        break;
    case 'delete_ingredient':
        (new \App\Controllers\InventoryController())->deleteIngredient();
        break;
    case 'checkout':
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login&redirect=cart');
            exit();
        }
        if (empty($_SESSION['cart'])) {
            header('Location: index.php?action=menu');
            exit();
        }
        
        $orderId = \App\Models\Order::add([
            'customer_name' => $_SESSION['user']['name'],
            'customer_email' => $_SESSION['user']['email'],
            'items' => $_SESSION['cart'],
            'total' => array_reduce($_SESSION['cart'], function($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0)
        ]);
        
        unset($_SESSION['cart']);
        echo "<script>alert('¡Pedido #$orderId confirmado! En breve estará listo.'); window.location.href='index.php?action=orders';</script>";
        break;
    case 'orders':
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit();
        }
        $orderDate = $_GET['date'] ?? null;
        $orders = \App\Models\Order::getUserHistory($_SESSION['user']['email'], $orderDate);
        require_once __DIR__ . '/../app/Views/orders.php';
        break;
    default:
        (new HomeController())->index();
        break;
}
