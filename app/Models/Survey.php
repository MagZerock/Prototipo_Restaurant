<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model {
    protected $table = 'surveys';
    public $timestamps = false;

    protected $fillable = [
        'customer_name',
        'rating',
        'comment'
    ];

    public static function getAll() {
        return self::orderBy('created_at', 'desc')->get()->toArray();
    }

    public static function add($data) {
        return self::create([
            'customer_name' => $data['customer'] ?? 'Anónimo',
            'rating' => (int)$data['rating'],
            'comment' => $data['comment'] ?? ''
        ]);
    }
}
