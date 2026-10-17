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
        Schema::create('astrologer_remedy_suggestions', function (Blueprint $table) {
            $table->id();
            $table->integer('astrologer_id');
            $table->integer('user_id');
            $table->integer('appointment_id');
            $table->text('products')->nullable(); // JSON array of product IDs
            $table->text('reports')->nullable(); // JSON array of report IDs
            $table->string('referral_code', 20)->nullable();
            $table->string('token', 64)->unique();
            $table->boolean('is_used')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_remedy_suggestions');
    }
};
