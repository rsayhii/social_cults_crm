<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'content',
        'category',
        'tags',
        'visibility',
        'teams',
        'related_client',
        'related_project',
        'related_task',
        'pinned',
        'user_id'
    ];

    protected $casts = [
        'tags' => 'array',
        'teams' => 'array',
        'pinned' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for formatted date
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at->format('M d, Y h:i A');
    }

     protected static function booted()
    {
        static::creating(function ($note) {
            if (auth()->check()) {
                $note->company_id = auth()->user()->company_id;
            }
        });
    }
}