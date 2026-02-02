<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Salaries table
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->date('salary_month');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('total_allowances', 10, 2)->default(0);
            $table->decimal('total_deductions', 10, 2)->default(0);
            $table->decimal('overtime_amount', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->integer('total_present_days')->default(0);
            $table->integer('total_absent_days')->default(0);
            $table->integer('total_late_days')->default(0);
            $table->integer('total_half_days')->default(0);
            $table->decimal('per_day_salary', 10, 2);
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'salary_month']);
        });

        // Salary details table
        Schema::create('salary_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_id')->constrained('salaries')->onDelete('cascade');
            $table->string('type'); // allowance, deduction, bonus
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        // Salary configs table
        Schema::create('salary_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('working_days_per_month')->default(22);
            $table->decimal('overtime_rate_per_hour', 10, 2)->default(0);
            $table->decimal('half_day_percentage', 5, 2)->default(50);
            $table->boolean('deduct_for_late')->default(false);
            $table->decimal('late_deduction_rate', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_details');
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('salary_configs');
    }
};