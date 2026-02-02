<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id','sender_id','message','company_id'];

    public function sender()
    {
        return $this->belongsTo(\App\Models\User::class, 'sender_id');
    }

    public function conversation()
    {
        return $this->belongsTo(\App\Models\Conversation::class, 'conversation_id');
    }
}
