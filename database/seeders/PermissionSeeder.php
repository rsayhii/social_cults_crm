<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'dashboard',
            'campaigns',
            'tasks',
            'attendance records',
            'users',
            'roles',
            'edit lead',
            'besdex',
            'my leads',
            'proposal',
            'my attendance',
            'todo',
            'task',
            'calender',
            'links and remark',
            'client serive interation',
            'salary',
            'invoice',
            'report',
            'notepad',
            'contact',
            'ticket records',
            'ticket raise',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
