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
        Schema::create('links', function (Blueprint $table) {
              $table->id();
            $table->string('type');                       // instagram, website, linkedin, etc.
            $table->string('title');                      // username or link title
            $table->string('url');                        // profile url or website
            $table->text('note')->nullable();             // optional notes
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('clicks')->default(0);        // analytics clicks
            $table->decimal('engagement', 5, 2)->default(0); // analytics engagement percentage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
