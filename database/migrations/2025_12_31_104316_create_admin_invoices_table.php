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
        Schema::create('admin_invoices', function (Blueprint $table) {
             $table->id();
            $table->string('invoice_number')->unique()->nullable();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->string('status')->default('pending'); // pending, paid, overdue, cancelled
            $table->string('currency')->default('₹');
            
            // Client Information
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('client_address')->nullable();
            $table->string('client_gstin')->nullable();
            
            // Company Information
            $table->string('company_name');
            $table->text('company_address');
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_gstin')->nullable();
            $table->text('company_bank_details')->nullable();
            $table->string('company_logo')->nullable();
            
            // Items stored as JSON
            $table->json('items');
            
            // Tax Details
            $table->decimal('tax_rate', 5, 2)->default(18.00);
            $table->string('tax_type')->default('cgst_sgst'); // cgst_sgst, igst
            
            // Totals
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            
            // Additional Info
            $table->text('description')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->string('signature')->nullable();
            
            // Payment Info
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->date('payment_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['invoice_number', 'status']);
            $table->index(['client_name', 'invoice_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_invoices');
    }
};
