<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = User::authenticate($email, $password);

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
            $email = $_POST['email'];
            
            if (User::exists($email)) {
                $error = "El correo electrónico ya está registrado";
                require_once __DIR__ . '/../Views/register.php';
                return;
            }

            $userId = User::add([
                'name' => $_POST['name'],
                'phone' => $_POST['phone'] ?? null,
                'email' => $email,
                'password' => $_POST['password'],
                'role' => 'customer'
            ]);

            header('Location: index.php?action=login');
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
