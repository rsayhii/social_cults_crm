<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperAdmin\Superadmin;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    public function run()
    {
        Superadmin::create([
            'name' => 'Main Superadmin',
            'email' => 'admin@enterprise.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
