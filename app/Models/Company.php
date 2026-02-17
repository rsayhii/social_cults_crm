<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Company extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'address',
        'email',
        'phone',
        'gstin',
        'bank_name',
        'account_number',
        'ifsc_code',
        'trial_ends_at',
        'is_paid',
        'status',
        'hidden_proposal_templates',
        'logo',
    ];



    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'hidden_proposal_templates' => 'array',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class)->whereHas('roles', function($query) {
            $query->where('name', 'admin');
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(\App\Models\SuperAdmin\Payment::class, 'customer_id');
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return 'https://www.socialcults.com/images/client/logo.png';
    }

    protected static function booted()
    {
        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name) . '-' . uniqid();
            }

            if (!$company->trial_ends_at) {
                $company->trial_ends_at = now()->addDays(30);
            }
        });
    }

}
