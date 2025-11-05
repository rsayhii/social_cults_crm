<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('status', ['lead', 'qualified', 'proposal', 'negotiation', 'client', 'lost'])->default('lead');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('industry')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->enum('source', ['website', 'referral', 'cold_outreach', 'social_media', 'event', 'other'])->default('website');
            $table->date('next_follow_up')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};