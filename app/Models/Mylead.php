<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mylead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_id',
        'response',
        'next_follow_up',
        'follow_up_time',
        'project_type',
        'status',
    ];

    
   protected $casts = [
        'next_follow_up' => 'date', // or 'datetime' if you store time too
    ];

    public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

public function client()
{
    return $this->belongsTo(\App\Models\Client::class, 'client_id');
}



}
