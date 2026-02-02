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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('category', ['client', 'project', 'task', 'meeting', 'idea', 'campaign', 'personal'])->default('personal');
            $table->json('tags')->nullable();
            $table->enum('visibility', ['private', 'team', 'public'])->default('private');
            $table->json('teams')->nullable();
            $table->string('related_client')->nullable();
            $table->string('related_project')->nullable();
            $table->string('related_task')->nullable();
            $table->boolean('pinned')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};