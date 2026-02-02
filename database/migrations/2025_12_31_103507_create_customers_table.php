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
        Schema::create('customers', function (Blueprint $table) {
              $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('business_name')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active', 'trial', 'expired', 'pending', 'cancelled'])->default('trial');
            $table->string('plan')->default('Trial');
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('license_key')->unique()->nullable();
            $table->string('login_url')->nullable();
            $table->date('trial_start_date')->nullable();
            $table->date('trial_end_date')->nullable();
            $table->date('subscription_start_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('renewal_attempts')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
