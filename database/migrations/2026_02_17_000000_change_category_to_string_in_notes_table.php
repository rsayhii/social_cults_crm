<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using raw SQL to ensure it works without doctrine/dbal if not present for enum changes
        // and to be explicit about the conversion.
        // Assuming MySQL/MariaDB
        DB::statement("ALTER TABLE notes MODIFY category VARCHAR(255) NOT NULL DEFAULT 'personal'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting back to ENUM.
        // Note: This will fail if there are values in 'category' that are not in the allowed list.
        // We will try to revert, but if it fails, it fails.
        // We use a try-catch or just the statement.
        
        // We need to be careful with the syntax.
        DB::statement("ALTER TABLE notes MODIFY category ENUM('client', 'project', 'task', 'meeting', 'idea', 'campaign', 'personal') NOT NULL DEFAULT 'personal'");
    }
};
