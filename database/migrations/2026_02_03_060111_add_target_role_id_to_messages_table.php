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
        Schema::table('messages', function (Blueprint $table) {
            // Target role for team-targeted messages (used by clients messaging specific teams)
            $table->unsignedBigInteger('target_role_id')->nullable()->after('conversation_id');
            $table->foreign('target_role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['target_role_id']);
            $table->dropColumn('target_role_id');
        });
    }
};
