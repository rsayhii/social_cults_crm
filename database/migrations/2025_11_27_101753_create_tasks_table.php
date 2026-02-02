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
       Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->date('due_date')->nullable();
            $table->json('attachments')->nullable(); // Store file paths as JSON array
            $table->boolean('assigned_to_team')->default(false); // Flag for team assignment
            $table->foreignId('assigned_role_id')->nullable()->constrained('roles')->onDelete('set null'); // Reference to Spatie roles
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Pivot table for task-user assignments (many-to-many)
        Schema::create('task_user', function (Blueprint $table) {
           $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_user');
        Schema::dropIfExists('tasks');
    }
};
