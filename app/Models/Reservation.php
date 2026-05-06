<?php
namespace App\Models;

class Reservation {
    private static $file = __DIR__ . '/../database/reservations.json';

    public static function getAll() {
        if (!file_exists(self::$file)) return [];
        return json_decode(file_get_contents(self::$file), true);
    }

    public static function add($data) {
        $reservations = self::getAll();
        $data['id'] = time();
        $reservations[] = $data;
        file_put_contents(self::$file, json_encode($reservations));
    }
}
