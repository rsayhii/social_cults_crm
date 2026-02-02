<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {

            // Address
            $table->text('address')->nullable()->after('slug');

            // Contact Info
            $table->string('email')->nullable()->after('address');
            $table->string('phone')->nullable()->after('email');

            // GST
            $table->string('gstin')->nullable()->after('phone');

            // Bank Details (Optional)
            $table->string('bank_name')->nullable()->after('gstin');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('account_number');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'email',
                'phone',
                'gstin',
                'bank_name',
                'account_number',
                'ifsc_code'
            ]);
        });
    }
};
