<?php
namespace App\Models;

class User {
    private static $file = __DIR__ . '/../database/users.json';

    public static function getAll() {
        if (!file_exists(self::$file)) {
            // Usuarios por defecto si el archivo no existe
            $defaults = [
                ['name' => 'Admin', 'email' => 'admin@biconoir.com', 'password' => 'admin123', 'role' => 'admin'],
                ['name' => 'Cliente Ejemplo', 'email' => 'customer@example.com', 'password' => 'customer123', 'role' => 'customer']
            ];
            file_put_contents(self::$file, json_encode($defaults));
            return $defaults;
        }
        return json_decode(file_get_contents(self::$file), true);
    }

    public static function add($data) {
        $users = self::getAll();
        $users[] = $data;
        file_put_contents(self::$file, json_encode($users));
    }

    public static function find($email, $password) {
        $users = self::getAll();
        foreach ($users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                return $user;
            }
        }
        return null;
    }
}
