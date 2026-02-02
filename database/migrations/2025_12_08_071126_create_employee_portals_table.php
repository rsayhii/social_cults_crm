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
        Schema::create('employee_portals', function (Blueprint $table) {
              $table->id();
            $table->string('employee_name');
            $table->string('employee_email');
            $table->string('employee_mobile');
            $table->string('employee_position');
            $table->string('sent_to');
            $table->string('subject');
            $table->enum('leave_type', ['casual', 'sick', 'paid', 'emergency', 'halfday', 'wfh']);
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('total_days');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->json('attachments')->nullable();
            $table->json('timeline')->nullable();
            $table->timestamp('applied_date')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status', 'leave_type']);
            $table->index('employee_email');
            $table->index('from_date');
            $table->index('to_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_portals');
    }
};
