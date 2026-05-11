<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model {
    protected $table = 'audit_logs';
    protected $primaryKey = 'log_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; 

    protected $fillable = [
        'log_id',
        'action_type',
        'user_id',
        'details'
    ];

    public static function add($actionType, $userId, $details) {
        return self::create([
            'log_id' => 'log_' . bin2hex(random_bytes(4)),
            'action_type' => $actionType,
            'user_id' => $userId,
            'details' => $details
        ]);
    }

    public static function getAll() {
        return self::orderBy('timestamp', 'desc')->get()->toArray();
    }
}
