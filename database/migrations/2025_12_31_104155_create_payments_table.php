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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null'); // ADDED
            $table->decimal('amount', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'upi', 'cash', 'cheque', 'online', 'wallet']);
            $table->string('transaction_id')->nullable()->unique();
            $table->enum('status', ['completed', 'pending', 'failed', 'refunded', 'cancelled', 'partially_paid'])->default('pending');
            $table->enum('payment_type', ['subscription', 'one_time', 'renewal', 'refund'])->default('one_time'); // ADDED
            $table->text('notes')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('currency', 3)->default('INR'); // Changed USD to INR
            $table->string('payment_gateway')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('payment_date');
            $table->index('created_at');
            $table->index('subscription_id'); // ADDED
            $table->index('payment_type'); // ADDED
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
