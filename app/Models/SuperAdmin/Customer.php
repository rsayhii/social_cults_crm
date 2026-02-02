<?php
// app/Models/Customer.php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'business_name',
        'address',
        'status',
        'plan',
        'amount_paid',
        'license_key',
        'login_url',
        'trial_start_date',
        'trial_end_date',
        'subscription_start_date',
        'subscription_end_date',
        'payment_method',
        'renewal_attempts'
    ];

    protected $casts = [
        'trial_start_date' => 'date',
        'trial_end_date' => 'date',
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function trials()
    {
        return $this->hasMany(Trial::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}