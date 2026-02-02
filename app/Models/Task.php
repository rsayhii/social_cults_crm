<?php
// Model: app/Models/Task.php
// Run: php artisan make:model Task

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
         'company_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'due_date',
        'attachments',
        'assigned_to_team',
        'assigned_role_id',
        'assigned_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'attachments' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'assigned_role_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scope for recent tasks
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc')->limit(50);
    }

    // Accessor for formatted due date
    public function getFormattedDueDateAttribute()
{
    return $this->due_date ? \Carbon\Carbon::parse($this->due_date)->format('M d, Y') : null;
}

    // Method to assign to team (role) using Spatie
    public function assignToTeam($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->update(['assigned_to_team' => true, 'assigned_role_id' => $roleId]);
        $users = $role->users; // Spatie method to get users with this role
        $this->users()->attach($users->pluck('id'));
    }

    // Method to assign to specific users
    public function assignToUsers($userIds)
    {
        $this->update(['assigned_to_team' => false, 'assigned_role_id' => null]);
        $this->users()->sync($userIds);
    }


    protected static function booted()
{
    // 🔐 Auto-assign company_id on create
    static::creating(function ($task) {
        if (auth()->check()) {
            $task->company_id = auth()->user()->company_id;
        }
    });

    // 🔒 Global company scope (VERY IMPORTANT)
    static::addGlobalScope('company', function ($query) {
        if (auth()->check()) {
            $query->where('company_id', auth()->user()->company_id);
        }
    });
}

}
