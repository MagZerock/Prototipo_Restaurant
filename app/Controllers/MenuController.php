<?php
namespace App\Controllers;

use App\Models\Dish;

class MenuController {
    public function index() {
        $dishes = Dish::getAll();
        require_once __DIR__ . '/../Views/menu.php';
    }
}
