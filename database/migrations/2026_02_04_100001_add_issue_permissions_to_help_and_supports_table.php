<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('help_and_supports', function (Blueprint $table) {
            if (!Schema::hasColumn('help_and_supports', 'issue_permissions')) {
                $table->json('issue_permissions')->nullable()->after('attachments');
            }
        });
    }

    public function down(): void
    {
        Schema::table('help_and_supports', function (Blueprint $table) {
            if (Schema::hasColumn('help_and_supports', 'issue_permissions')) {
                $table->dropColumn('issue_permissions');
            }
        });
    }
};
