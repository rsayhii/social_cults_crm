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
            // Only add columns if they don't exist
            if (!Schema::hasColumn('conversations', 'type')) {
                $table->enum('type', ['direct', 'group'])->default('direct')->after('id');
            }

            if (!Schema::hasColumn('conversations', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('id');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            }

            if (!Schema::hasColumn('conversations', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            if (Schema::hasColumn('conversations', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('conversations', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
            if (Schema::hasColumn('conversations', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
