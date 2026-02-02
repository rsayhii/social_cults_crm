<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Drop Spatie default unique index
            $table->dropUnique('roles_name_guard_name_unique');

            // Add tenant-aware unique index
           $table->unique(['name', 'guard_name']);

        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_company_unique');
            $table->unique(['name', 'guard_name'], 'roles_name_guard_name_unique');
        });
    }
};
