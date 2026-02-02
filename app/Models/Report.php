<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'date',
        'summary',
        'status',
        'tasks',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'tasks' => 'array',
    ];

     protected static function booted()
    {
        static::creating(function ($report) {
            if (auth()->check()) {
                $report->company_id = auth()->user()->company_id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}