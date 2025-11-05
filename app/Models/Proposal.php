<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
     use HasFactory;


     protected $fillable = [
        'user_id',
        'client_id',
        'title',
        'description',
        'amount',
        'status',
        'file_path',
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
