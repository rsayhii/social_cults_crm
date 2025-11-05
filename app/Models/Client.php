<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'status',
        'priority',
        'industry',
        'budget',
        'source',
        'next_follow_up',
        'notes'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'next_follow_up' => 'date',
    ];

    public function leadAction()
{
    return $this->hasOne(\App\Models\Mylead::class, 'client_id');
}
}