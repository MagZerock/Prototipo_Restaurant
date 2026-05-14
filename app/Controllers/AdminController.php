<?php
namespace App\Controllers;

use App\Models\Dish;
use App\Models\Reservation;
use App\Models\Survey;
use App\Models\Order;

class AdminController {
    public function dashboard() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'administrator'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $dishes = Dish::getAll();
        $reservations = Reservation::getAll();
        
        // Lógica de filtrado de pedidos
        $orderDate = $_GET['order_date'] ?? date('Y-m-d');
        $query = Order::with(['details.menuItem']);

        if (!empty($orderDate)) {
            $start = strtotime($orderDate . ' 00:00:00');
            $end = strtotime($orderDate . ' 23:59:59');
            $query->whereBetween('order_date_time', [$start, $end]);
        }
        
        $ordersRaw = $query->orderBy('order_date_time', 'desc')->get();
        $orders = Order::mapCompatibility($ordersRaw);
        
        $ingredients = \App\Models\Ingredient::getAll();

        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function addDish() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => (float)$_POST['price'],
                'image' => $_POST['image'],
                'ingredients' => $this->processIngredientInput($_POST['ingredients'] ?? [])
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
                'image' => $_POST['image'],
                'ingredients' => $this->processIngredientInput($_POST['ingredients'] ?? [])
            ];
            
            try {
                Dish::updateDish($id, $data);
            } catch (\Exception $e) {
                echo "<script>alert('" . $e->getMessage() . "'); window.location.href='index.php?action=admin_dashboard';</script>";
                exit();
            }
        }
        header('Location: index.php?action=admin_dashboard');
        exit();
    }

    private function processIngredientInput($input) {
        $filtered = [];
        foreach ($input as $sku => $meta) {
            if (isset($meta['selected']) && $meta['selected'] == '1') {
                $filtered[$sku] = (float)($meta['quantity'] ?? 0);
            }
        }
        return $filtered;
    }

    public function deleteDish() {
        if (isset($_GET['id'])) {
            try {
                Dish::deleteDish($_GET['id']);
            } catch (\Exception $e) {
                echo "<script>alert('" . $e->getMessage() . "'); window.location.href='index.php?action=admin_dashboard';</script>";
                exit();
            }
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

    public function reservations() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'administrator'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $dateFilter = $_GET['date'] ?? null;
        
        if ($dateFilter) {
            $reservations = Reservation::with('user')->where('date', $dateFilter)->orderBy('time', 'asc')->get()->toArray();
        } else {
            $reservations = Reservation::with('user')->orderBy('date', 'asc')->orderBy('time', 'asc')->get()->toArray();
        }

        require_once __DIR__ . '/../Views/admin/adminreservations.php';
    }

    public function updateReservationStatus() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'administrator'])) {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_GET['id']) && isset($_GET['status'])) {
            $reservation = Reservation::find($_GET['id']);
            if ($reservation) {
                $reservation->status = $_GET['status'];
                $reservation->save();
            }
        }
        
        $redirectUrl = 'index.php?action=admin_reservations';
        if (isset($_GET['date'])) {
            $redirectUrl .= '&date=' . $_GET['date'];
        }
        
        header('Location: ' . $redirectUrl);
        exit();
    }

    public function surveys() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'administrator'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $surveys = Survey::orderBy('id', 'desc')->get()->toArray();
        require_once __DIR__ . '/../Views/admin/adminsurveys.php';
    }
}
