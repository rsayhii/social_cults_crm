<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // Type: 'direct' for 1:1 chats, 'group' for role group chats
            $table->enum('type', ['direct', 'group'])->default('direct')->after('id');

            // Role ID for group chats - links to the role this group is for
            $table->unsignedBigInteger('role_id')->nullable()->after('type');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            // Group name for display purposes
            $table->string('name')->nullable()->after('role_id');
        });

        // Make team_id and client_id nullable for group chats
        Schema::table('conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable()->change();
            $table->unsignedBigInteger('client_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['type', 'role_id', 'name']);
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable(false)->change();
            $table->unsignedBigInteger('client_id')->nullable(false)->change();
        });
    }
};

