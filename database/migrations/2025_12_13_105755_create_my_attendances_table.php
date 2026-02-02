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
        Schema::create('my_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade'); // Assuming users table has employees
            $table->date('date');
            $table->time('punch_in')->nullable();
            $table->time('punch_out')->nullable();
            $table->time('lunch_start')->nullable();
            $table->time('lunch_end')->nullable();
            $table->time('work_hours')->nullable(); // e.g., '08:15'
            $table->time('break_hours')->nullable(); // e.g., '00:45'
            $table->string('location')->nullable();
            $table->enum('status', ['Present', 'Late', 'Half Day', 'Absent'])->default('Absent');
            $table->integer('overtime_seconds')->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_attendances');
    }
};
