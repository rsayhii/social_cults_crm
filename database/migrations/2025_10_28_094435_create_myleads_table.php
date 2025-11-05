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
        Schema::create('myleads', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('user_id'); // logged in user
        $table->unsignedBigInteger('client_id')->nullable();
        $table->text('response')->nullable();
        $table->text('project_type')->nullable();
        $table->date('next_follow_up')->nullable();
        $table->time('follow_up_time')->nullable();
        $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('myleads');
    }
};
