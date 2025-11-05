<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Myattendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'break_time',
        'late_minutes',
        'overtime_minutes',
        'production_hours',
        'total_hours'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calculate production hours
    public function getProductionHoursAttribute($value)
    {
        if ($this->check_in && $this->check_out) {
            $totalMinutes = $this->check_out->diffInMinutes($this->check_in);
            $breakMinutes = $this->break_time ? (int)$this->break_time : 0;
            $productionMinutes = $totalMinutes - $breakMinutes;
            
            return floor($productionMinutes / 60) . 'h ' . ($productionMinutes % 60) . 'm';
        }
        
        return $value;
    }

    // Calculate total hours
    public function getTotalHoursAttribute($value)
    {
        if ($this->check_in && $this->check_out) {
            $totalMinutes = $this->check_out->diffInMinutes($this->check_in);
            return floor($totalMinutes / 60) . 'h ' . ($totalMinutes % 60) . 'm';
        }
        
        return $value;
    }
}