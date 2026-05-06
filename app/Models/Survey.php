<?php
namespace App\Models;

class Survey {
    private static $file = __DIR__ . '/../database/surveys.json';

    public static function getAll() {
        if (!file_exists(self::$file)) return [];
        return json_decode(file_get_contents(self::$file), true);
    }

    public static function add($data) {
        $surveys = self::getAll();
        $data['id'] = time();
        $surveys[] = $data;
        file_put_contents(self::$file, json_encode($surveys));
    }
}
