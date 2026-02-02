<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'company_id',
        'type',
        'title',
        'url',
        'note',
        'status',
        'clicks',
        'engagement',
    ];


     protected static function booted()
    {
        static::creating(function ($link) {
            if (auth()->check()) {
                $link->company_id = auth()->user()->company_id;
            }
        });
    }
}
