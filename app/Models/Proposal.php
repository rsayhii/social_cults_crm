<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;


    protected $fillable = [
        'company_id',
        'user_id',
        'client_id',
        'title',
        'description',
        'content',
        'settings',
        'amount',
        'status',
        'file_path',
    ];

    protected $casts = [
        'settings' => 'array',
    ];


    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
