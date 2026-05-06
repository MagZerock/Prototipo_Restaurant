<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = User::find($email, $password);

            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: index.php?action=home');
                exit();
            } else {
                $error = "Credenciales incorrectas";
            }
        }
        require_once __DIR__ . '/../Views/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            User::add([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'role' => 'customer'
            ]);
            // Autologin after register
            $_SESSION['user'] = [
                'name' => $_POST['name'],
                'role' => 'customer'
            ];
            header('Location: index.php?action=home');
            exit();
        }
        require_once __DIR__ . '/../Views/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
