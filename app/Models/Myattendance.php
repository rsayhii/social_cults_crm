<?php
// app/Models/MyAttendance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
         'company_id',
        'employee_id',
        'date',
        'punch_in',
        'punch_out',
        'lunch_start',
        'lunch_end',
        'work_hours',
        'break_hours',
        'location',
        'latitude',
        'longitude',
        'accuracy',
        'distance',
        'is_within_range',
        'status',
        'overtime_seconds',
    ];

    protected $casts = [
        'date' => 'date',
        'punch_in' => 'datetime',
        'punch_out' => 'datetime',
        'lunch_start' => 'datetime',
        'lunch_end' => 'datetime',
        'is_within_range' => 'boolean',
        'distance' => 'decimal:2',
        'accuracy' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];


      protected static function booted()
    {
        static::creating(function ($attendance) {
            if (auth()->check()) {
                $attendance->company_id = auth()->user()->company_id;
            }
        });
    }

    // Relationship: Belongs to Employee
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Scope for current month
    public function scopeForMonth($query, $month, $year = null)
    {
        $year = $year ?? now()->year;
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    // Calculate work hours
    // public function getCalculatedWorkHoursAttribute()
    // {
    //     if (!$this->punch_in || !$this->punch_out) return '00:00';

    //     $total = $this->punch_out->diffInSeconds($this->punch_in);
    //     if ($this->lunch_start && $this->lunch_end) {
    //         $break = $this->lunch_end->diffInSeconds($this->lunch_start);
    //         $total -= $break;
    //     }

    //     $hours = floor($total / 3600);
    //     $minutes = floor(($total % 3600) / 60);
    //     return sprintf('%02d:%02d', $hours, $minutes);
    // }

    // Calculate break hours
    public function getCalculatedBreakHoursAttribute()
    {
        if (!$this->lunch_start || !$this->lunch_end) return '00:00';

        $break = $this->lunch_end->diffInSeconds($this->lunch_start);
        $hours = floor($break / 3600);
        $minutes = floor(($break % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    // In MyAttendance.php model
public function breaks()
{
    return $this->hasMany(MyAttendanceBreak::class, 'attendance_id');
}

public function getTotalBreakSecondsAttribute()
{
    return $this->breaks->sum('break_seconds');
}

public function getCalculatedWorkHoursAttribute()
{
    if (!$this->punch_in || !$this->punch_out) return '00:00';

    $total = $this->punch_out->diffInSeconds($this->punch_in);
    $total -= $this->total_break_seconds; // Subtract all breaks

    return gmdate('H:i', max($total, 0));
}
}