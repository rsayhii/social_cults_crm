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
        Schema::create('reports', function (Blueprint $table) {
             $table->id();
            $table->date('date');
            $table->text('summary');
            $table->enum('status', ['completed', 'inprogress', 'pending'])->default('pending');
            $table->json('tasks'); // Store tasks as JSON
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            // Indexes for better performance
            $table->index(['date', 'user_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
