<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('links')) {
            Schema::table('links', function (Blueprint $table) {
                if (!Schema::hasColumn('links', 'company_id')) {
                    $table->unsignedBigInteger('company_id')->nullable()->index()->after('id');
                }
                if (!Schema::hasColumn('links', 'category')) {
                    $table->enum('category', ['marketing', 'support', 'internal'])->default('marketing')->after('type');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('links')) {
            Schema::table('links', function (Blueprint $table) {
                if (Schema::hasColumn('links', 'category')) {
                    $table->dropColumn('category');
                }
                if (Schema::hasColumn('links', 'company_id')) {
                    $table->dropIndex(['company_id']);
                    $table->dropColumn('company_id');
                }
            });
        }
    }
};

