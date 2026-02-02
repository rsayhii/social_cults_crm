<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'position',
        'email',
        'phone',
        'country',
        'avatar',
        'rating',
        'instagram',
        'facebook',
        'whatsapp',
        'linkedin',
        'notes'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'social' => 'array'
    ];

       protected static function booted()
    {
        static::creating(function ($contact) {
            if (auth()->check()) {
                $contact->company_id = auth()->user()->company_id;
            }
        });
    }
}