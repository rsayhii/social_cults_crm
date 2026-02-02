<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectManagement extends Model
{
    use SoftDeletes;

    protected $table = 'project_managements';

    protected $fillable = [
        'company_id',
        'name',
        'client',
        'owner',
        'team',
        'status',
        'priority',
        'start_date',
        'deadline',
        'progress',
        'budget',
        'description',
        'client_details',
        'owner_details',
        'team_members',
        'timeline',
        'tasks'
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'progress' => 'integer',
        'budget' => 'decimal:2',
        'client_details' => 'array',
        'owner_details' => 'array',
        'team_members' => 'array',
        'timeline' => 'array',
        'tasks' => 'array'
    ];

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope for filtering by team
     */
    public function scopeByTeam($query, $team)
    {
        if ($team && $team !== 'all') {
            return $query->where('team', $team);
        }
        return $query;
    }

    /**
     * Scope for filtering by priority
     */
    public function scopeByPriority($query, $priority)
    {
        if ($priority && $priority !== 'all') {
            return $query->where('priority', $priority);
        }
        return $query;
    }

    /**
     * Scope for searching
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%")
                  ->orWhere('owner', 'like', "%{$search}%")
                  ->orWhere('team', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    protected static function booted()
    {
        static::creating(function ($project) {
            if (auth()->check()) {
                $project->company_id = auth()->user()->company_id;
            }
        });
    }
}