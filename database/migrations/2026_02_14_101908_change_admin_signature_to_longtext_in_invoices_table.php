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
        // Schema::table('invoices', function (Blueprint $table) {
        //     $table->longText('admin_signature')->change();
        // });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE invoices MODIFY admin_signature LONGTEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->text('admin_signature')->change();
        });
    }
};
