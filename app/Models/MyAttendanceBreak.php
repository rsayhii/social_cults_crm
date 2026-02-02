<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyAttendanceBreak extends Model
{
    protected $fillable = [
         'company_id',
        'attendance_id',
        'break_in',
        'break_out',
        'break_seconds',
    ];
     protected static function booted()
    {
        static::creating(function ($break) {
            if (auth()->check()) {
                $break->company_id = auth()->user()->company_id;
            }
        });
    }

    public function attendance()
    {
        return $this->belongsTo(MyAttendance::class);
    }
}
