<?php

namespace App\Observers;

use App\Models\Company;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        // 1. Create Admin Role scoped to this company
        // We use the overridden Role::create which avoids global uniqueness check
        $adminRole = \App\Models\Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
            'company_id' => $company->id
        ]);

        // 2. Assign all existing permissions that should be available to admin
        // Note: Make sure permissions exist in 'permissions' table
        $permissions = \Spatie\Permission\Models\Permission::all();
        $adminRole->syncPermissions($permissions);
    }

    /**
     * Handle the Company "updated" event.
     */
    public function updated(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "restored" event.
     */
    public function restored(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "force deleted" event.
     */
    public function forceDeleted(Company $company): void
    {
        //
    }
}
