<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model {
    protected $table = 'financial_reports';
    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'simulation_days',
        'total_profit_loss',
        'profit_loss_per_day'
    ];

    public static function getAll() {
        return self::orderBy('created_at', 'desc')->get()->toArray();
    }

    public static function save($data) {
        return self::create($data);
    }
}
