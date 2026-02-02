<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['team_id','client_id','company_id'];

    public function team()
    {
        return $this->belongsTo(\App\Models\User::class, 'team_id');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\User::class, 'client_id');
    }

    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class, 'conversation_id')->orderBy('id', 'asc');
    }
}
