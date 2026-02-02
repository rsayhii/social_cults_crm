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
        Schema::create('mylead_histories', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('mylead_id');
            $table->unsignedBigInteger('user_id');
            $table->json('changes')->nullable(); // JSON of field changes: {'field': {'old': 'value', 'new': 'value'}}
            $table->text('response')->nullable(); // Optional: snapshot of response at this history point
            $table->timestamps();

            $table->foreign('mylead_id')->references('id')->on('myleads')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mylead_histories');
    }
};
