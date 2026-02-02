<?php

// app/Models/HolidayOverride.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HolidayOverride extends Model
{
    protected $fillable = ['holiday_id','mark_working','created_by'];

    public function holiday() { return $this->belongsTo(Holiday::class); }
    public function creator() { return $this->belongsTo(User::class,'created_by'); }
}

