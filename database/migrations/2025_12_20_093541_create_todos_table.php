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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('assigned_users')->nullable();
            $table->longText('description')->nullable();
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->date('due_date')->nullable();
            $table->enum('category', ['projects', 'internal', 'reminder', 'social'])->default('projects');
            $table->enum('status', ['pending', 'inprogress', 'completed', 'onhold'])->default('pending');
            $table->boolean('starred')->default(false);
            $table->boolean('completed')->default(false);
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
