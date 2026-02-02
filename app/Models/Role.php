<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name', 'company_id'];

    /**
     * Relationship to company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope roles to a specific company
     */
    public function scopeForCompany(Builder $query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Boot method to auto-set company_id
     */
   protected static function boot()
{
    parent::boot();

    static::creating(function ($role) {
        if (!$role->company_id) {
            throw new \RuntimeException('company_id is required when creating a role.');
        }
    });
}


public static function createForCompany(array $attributes, int $companyId)
{
    $attributes['company_id'] = $companyId;
    return static::create($attributes);
}


    /**
     * Override create method to fix multi-tenant uniqueness check.
     */
  public static function create(array $attributes = [])
{
    if (!isset($attributes['company_id'])) {
        throw new \RuntimeException(
            'Use Role::createForCompany(). Direct Role::create() is forbidden.'
        );
    }

    $attributes['guard_name'] = $attributes['guard_name']
        ?? \Spatie\Permission\Guard::getDefaultName(static::class);

    $existing = static::where('name', $attributes['name'])
        ->where('guard_name', $attributes['guard_name'])
        ->where('company_id', $attributes['company_id'])
        ->first();

    if ($existing) {
        return $existing;
    }

    return static::query()->create($attributes);
}

}
