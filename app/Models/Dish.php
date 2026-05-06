<?php
namespace App\Models;

class Dish {
    private static $file = __DIR__ . '/../database/dishes.json';

    public static function getAll() {
        if (!file_exists(self::$file)) {
            // Initial mock data
            $initial = [
                ['id' => 1, 'name' => 'Hamburguesa Biconoir', 'description' => 'Carne premium y queso cheddar.', 'price' => 12.50, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=500'],
                ['id' => 2, 'name' => 'Ensalada Green', 'description' => 'Mix de lechugas y aguacate.', 'price' => 8.90, 'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=500']
            ];
            file_put_contents(self::$file, json_encode($initial));
            return $initial;
        }
        return json_decode(file_get_contents(self::$file), true);
    }

    public static function add($data) {
        $dishes = self::getAll();
        $data['id'] = time(); // Simple unique ID
        $dishes[] = $data;
        file_put_contents(self::$file, json_encode($dishes));
    }

    public static function update($id, $data) {
        $dishes = self::getAll();
        $updated = false;
        foreach ($dishes as &$dish) {
            if ((string)$dish['id'] === (string)$id) {
                $dish['name'] = $data['name'];
                $dish['description'] = $data['description'];
                $dish['price'] = (float)$data['price'];
                $dish['image'] = $data['image'];
                $updated = true;
                break;
            }
        }
        if ($updated) {
            file_put_contents(self::$file, json_encode($dishes, JSON_PRETTY_PRINT));
        }
        return $updated;
    }
}
