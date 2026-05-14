<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    protected $table = 'reservations';
    protected $primaryKey = 'reservation_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'reservation_id',
        'customer_id',
        'date',
        'time',
        'number_of_people',
        'status',
        'notes'
    ];

    public static function getAll() {
        return self::orderBy('date', 'desc')->orderBy('time', 'desc')->get()->toArray();
    }

    public static function add($data) {
        return self::create([
            'reservation_id' => 'res_' . bin2hex(random_bytes(4)),
            'customer_id' => $_SESSION['user']['user_id'] ?? null,
            'date' => $data['date'] ?? date('Y-m-d'),
            'time' => $data['time'] ?? '19:00',
            'number_of_people' => $data['pax'] ?? $data['number_of_people'] ?? 2,
            'status' => 'Confirmed',
            'notes' => $data['notes'] ?? $data['type'] ?? ''
        ]);
    }

    public function user() {
        return $this->belongsTo(User::class, 'customer_id', 'user_id');
    }
}
