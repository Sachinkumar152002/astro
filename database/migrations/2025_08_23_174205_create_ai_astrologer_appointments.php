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
        Schema::create('ai_astrologer_appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('astrologer_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('mode')->default('chat')->comment('chat, call, video');
            $table->json('form_data')->nullable();
            $table->decimal('per_min_charge', 10, 2)->default(0);
            $table->decimal('charges', 10, 2)->default(0);
            $table->datetime('start_at')->useCurrent();
            $table->datetime('end_at')->nullable();
            $table->integer('paid')->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_astrologer_appointments');
    }
};
