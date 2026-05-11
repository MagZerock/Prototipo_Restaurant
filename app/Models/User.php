<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'password_hash',
        'role'
    ];

    public static function getAll() {
        return self::all()->toArray();
    }

    public static function add($data) {
        $userId = $data['user_id'] ?? 'u_' . bin2hex(random_bytes(4));
        
        $user = self::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password_hash' => $data['password'] ?? $data['password_hash'],
            'role' => $data['role'] ?? 'customer'
        ]);

        return $user->user_id;
    }

    public static function authenticate($email, $password) {
        $user = self::where('email', $email)
                    ->where('password_hash', $password)
                    ->first();
        return $user ? $user->toArray() : null;
    }
}
