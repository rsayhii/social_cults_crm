<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['team_id', 'client_id', 'company_id', 'type', 'role_id', 'name'];

    /**
     * Get the role this group chat belongs to (for group chats)
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id');
    }

    /**
     * Get participants of this conversation (for group chats)
     */
    public function participants()
    {
        return $this->belongsToMany(\App\Models\User::class, 'conversation_participants', 'conversation_id', 'user_id')
            ->withPivot('joined_at', 'last_read_at')
            ->withTimestamps();
    }

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

    /**
     * Check if this is a group chat
     */
    public function isGroupChat()
    {
        return $this->type === 'group';
    }

    /**
     * Check if this is a direct chat
     */
    public function isDirectChat()
    {
        return $this->type === 'direct';
    }
}

