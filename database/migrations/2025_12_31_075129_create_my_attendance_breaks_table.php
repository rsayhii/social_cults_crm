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
        Schema::create('my_attendance_breaks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('attendance_id')
          ->constrained('my_attendances')
          ->onDelete('cascade');

    $table->time('break_in');
    $table->time('break_out')->nullable();
    $table->integer('break_seconds')->default(0);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_attendance_breaks');
    }
};
