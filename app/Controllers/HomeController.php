<?php
namespace App\Controllers;

use App\Models\Reservation;
use App\Models\Survey;

class HomeController {
    public function index() {
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
                'customer' => $_POST['customer'],
                'date' => $_POST['date'],
                'time' => $_POST['time'],
                'pax' => $_POST['pax'],
                'type' => $_POST['type'], // Normal o Evento
                'notes' => $_POST['notes']
            ]);
            echo "<script>alert('¡Reserva recibida! Nos pondremos en contacto pronto.'); window.location.href='index.php';</script>";
            exit();
        }
        require_once __DIR__ . '/../Views/reservations.php';
    }

    public function survey() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Survey::add([
                'customer' => $_POST['customer'],
                'rating' => (int)$_POST['rating'],
                'comment' => $_POST['comment']
            ]);
            echo "<script>alert('¡Gracias por tu opinión! Nos ayuda a mejorar.'); window.location.href='index.php';</script>";
            exit();
        }
        require_once __DIR__ . '/../Views/survey.php';
    }
}
