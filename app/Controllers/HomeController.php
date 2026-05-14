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

    public function reservations() {
        
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit();
        }
        
        $userId = $_SESSION['user']['user_id'];
        $today = date('Y-m-d');
        
        $userActiveCount = Reservation::where('customer_id', $userId)
            ->whereIn('status', ['Pending', 'Pendiente', 'Confirmed', 'Confirmada'])
            ->count();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'];
            $time = $_POST['time'];

            if ($userActiveCount >= 2) {
                echo "<script>alert('No puedes tener más de 2 reservas activas. Por favor cancela una antes de crear otra.'); window.history.back();</script>";
                return;
            }

            $sameDayCount = Reservation::where('customer_id', $userId)
                ->where('date', $date)
                ->whereIn('status', ['Pending', 'Pendiente', 'Confirmed', 'Confirmada'])
                ->count();
                
            if ($sameDayCount >= 1) {
                echo "<script>alert('Ya tienes una reserva para esta fecha. Solo se permite 1 por día.'); window.history.back();</script>";
                return;
            }

            $count = Reservation::where('date', $date)->where('time', $time)->count();

            if ($count >= 3) {
                echo "<script>alert('Lo sentimos, ya no hay mesas disponibles para esa fecha y hora. Por favor, elige otra.'); window.history.back();</script>";
                return;
            }

            Reservation::add([
                'customer_name' => $_POST['customer'] ?? $_SESSION['user']['username'] ?? 'Cliente',
                'date' => $date,
                'time' => $time,
                'pax' => $_POST['pax'],
                'type' => $_POST['type'],
                'notes' => $_POST['notes']
            ]);
            echo "<script>alert('¡Tu mesa ha sido reservada con éxito!'); window.location.href='index.php?action=reservations';</script>";
            return;
        }
        
        $existingReservations = Reservation::where('date', '>=', $today)->get()->toArray();
        
        $userReservations = Reservation::where('customer_id', $userId)
            ->whereIn('status', ['Pending', 'Pendiente', 'Confirmed', 'Confirmada'])
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get()->toArray();
        
        require_once __DIR__ . '/../Views/reservations.php';
    }

    public function cancelReservation() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $userId = $_SESSION['user']['user_id'];
        $resId = $_GET['id'] ?? null;

        if ($resId) {
            $reservation = Reservation::find($resId);
            if ($reservation && $reservation->customer_id === $userId) {
                $reservation->status = 'Cancelled';
                $reservation->save();
                echo "<script>alert('Reserva cancelada exitosamente.'); window.location.href='index.php?action=reservations';</script>";
                return;
            }
        }
        
        echo "<script>alert('No se pudo cancelar la reserva.'); window.history.back();</script>";
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
