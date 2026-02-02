<?php


// SalaryConfig.php Model
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryConfig extends Model
{
    protected $fillable = [
        'company_id',
        'working_days_per_month',
        'overtime_rate_per_hour',
        'half_day_percentage',
        'deduct_for_late',
        'late_deduction_rate'
    ];
    
    protected $casts = [
        'deduct_for_late' => 'boolean'
    ];

     protected static function booted()
    {
        static::creating(function ($config) {
            if (auth()->check()) {
                $config->company_id = auth()->user()->company_id;
            }
        });
    }
}