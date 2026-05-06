<?php
namespace App\Controllers;

use App\Models\Dish;
use App\Models\Reservation;
use App\Models\Survey;
use App\Models\Order;

class AdminController {
    public function dashboard() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }

        $dishes = Dish::getAll();
        $reservations = Reservation::getAll();
        $surveys = Survey::getAll();
        $orders = Order::getAll();

        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function addDish() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => time(),
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => (float)$_POST['price'],
                'image' => $_POST['image'],
                'ingredients' => [] // Por ahora vacío al añadir nuevo, se puede mejorar
            ];
            Dish::add($data);
        }
        header('Location: index.php?action=admin_dashboard');
        exit();
    }

    public function editDish() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => (float)$_POST['price'],
                'image' => $_POST['image']
            ];
            Dish::update($id, $data);
        }
        header('Location: index.php?action=admin_dashboard');
        exit();
    }

    public function updateOrderStatus() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            Order::updateStatus($_GET['id'], $_GET['status']);
        }
        header('Location: index.php?action=admin_dashboard');
        exit();
    }
}
