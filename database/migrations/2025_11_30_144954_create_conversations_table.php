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
        Schema::create('conversations', function (Blueprint $table) {
        $table->id();
            // team_id is a user id who acts as team (any role except admin/client)
            $table->unsignedBigInteger('team_id');
            // client_id is a user id with role 'client'
            $table->unsignedBigInteger('client_id');
            $table->timestamps();

            $table->index('team_id');
            $table->index('client_id');

            $table->foreign('team_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
