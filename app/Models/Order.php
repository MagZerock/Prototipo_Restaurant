<?php
namespace App\Models;

class Order {
    private static $file = __DIR__ . '/../database/orders.json';

    public static function getAll() {
        if (!file_exists(self::$file)) {
            file_put_contents(self::$file, json_encode([]));
            return [];
        }
        return json_decode(file_get_contents(self::$file), true);
    }

    public static function add($data) {
        $orders = self::getAll();
        $data['id'] = time() . rand(100, 999);
        $data['status'] = 'En Preparación';
        $data['created_at'] = date('Y-m-d H:i:s');
        $orders[] = $data;
        file_put_contents(self::$file, json_encode($orders));
        return $data['id'];
    }

    public static function getByUser($email) {
        $orders = self::getAll();
        return array_filter($orders, function($o) use ($email) {
            return $o['customer_email'] === $email;
        });
    }

    public static function updateStatus($id, $status) {
        $orders = self::getAll();
        foreach ($orders as &$o) {
            if ((string)$o['id'] === (string)$id) {
                $o['status'] = $status;
                break;
            }
        }
        file_put_contents(self::$file, json_encode($orders));
    }
}
