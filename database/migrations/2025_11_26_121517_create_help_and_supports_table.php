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
        Schema::create('help_and_supports', function (Blueprint $table) {
              $table->id();
            $table->string('ticket_id')->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['website', 'social-media', 'ads-manager', 'email', 'billing', 'others']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->enum('status', ['open', 'in-progress', 'completed', 'closed'])->default('open');
            $table->foreignId('client_id')->constrained('users');
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users');
            $table->string('assigned_team')->nullable();
            $table->timestamp('sla_due_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->json('conversations')->nullable(); // Store all conversations as JSON
            $table->json('attachments')->nullable();   // Store all attachments as JSON
            $table->timestamps();
            
            $table->index(['status', 'priority']);
            $table->index('sla_due_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_and_supports');
    }
};
