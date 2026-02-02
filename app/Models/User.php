<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'salary',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user');
    }


    public function isTeam()
{
    return $this->roles()->whereNotIn('name', ['admin','client'])->exists();
}




public function attendanceRecords() 
{ 
    return $this->hasMany(AttendanceRecord::class); 
}
public function leaves() 
{ 
    return $this->hasMany(Leave::class); 
}
public function salarySlips() 
{ 
    return $this->hasMany(SalarySlip::class); 
}


public function company()
{
    return $this->belongsTo(Company::class);
}





}
