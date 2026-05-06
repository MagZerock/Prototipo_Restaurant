<?php
namespace App\Controllers;

use App\Models\Dish;
use App\Models\Reservation;
use App\Models\Survey;

class HomeController {
    public function index() {
        $dishes = Dish::getAll();
        require_once __DIR__ . '/../Views/home.php';
    }

    public function about() {
        require_once __DIR__ . '/../Views/about.php';
    }

    public function locations() {
        require_once __DIR__ . '/../Views/locations.php';
    }

    public function reservations() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Reservation::add([
                'customer' => $_POST['name'],
                'date' => $_POST['date'],
                'type' => $_POST['type']
            ]);
            echo "<script>alert('Reserva enviada'); window.location.href='index.php';</script>";
            return;
        }
        require_once __DIR__ . '/../Views/reservations.php';
    }

    public function survey() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Survey::add([
                'customer' => $_POST['customer'] ?? 'Anónimo',
                'rating' => (int)$_POST['rating'],
                'comment' => $_POST['comment']
            ]);
            echo "<script>alert('¡Gracias por tu feedback!'); window.location.href='index.php';</script>";
            return;
        }
        require_once __DIR__ . '/../Views/survey.php';
    }
}
