<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'priority',
        'due_date',
        'category',
        'status',
        'starred',
        'completed',
        'assigned_users',
    ];

    protected $casts = [
        'assigned_users' => 'array',
        'starred' => 'boolean',
        'completed' => 'boolean',
        'due_date' => 'date',
    ];

  protected static function booted()
{
    // 🔒 COMPANY ISOLATION (MOST IMPORTANT)
    static::addGlobalScope('company', function ($query) {
        if (auth()->check()) {
            $query->where('company_id', auth()->user()->company_id);
        }
    });

    // ✅ AUTO-SET COMPANY ON CREATE
    static::creating(function ($todo) {
        if (auth()->check()) {
            $todo->company_id = auth()->user()->company_id;
        }
    });
}


}
