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
        Schema::create('project_managements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('client');
            $table->string('owner');
            $table->string('team');
            $table->enum('status', ['pending', 'in-progress', 'review', 'completed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->date('start_date');
            $table->date('deadline');
            $table->integer('progress')->default(0);
            $table->decimal('budget', 15, 2)->nullable();
            $table->text('description')->nullable();
            
            // Client details as JSON
            $table->json('client_details')->nullable();
            
            // Owner details as JSON
            $table->json('owner_details')->nullable();
            
            // Team members as JSON
            $table->json('team_members')->nullable();
            
            // Timeline as JSON
            $table->json('timeline')->nullable();
            
            // Tasks as JSON
            $table->json('tasks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_managements');
    }
};
