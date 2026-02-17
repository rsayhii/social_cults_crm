<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->time('office_start_time')->nullable();
            $table->time('office_end_time')->nullable();
            $table->decimal('total_working_hours', 4, 2)->nullable(); // e.g., 8.5
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'office_start_time', 'office_end_time', 'total_working_hours']);
        });
    }
};
