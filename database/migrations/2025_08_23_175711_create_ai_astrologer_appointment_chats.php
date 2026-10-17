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
        Schema::create('ai_astrologer_appointment_chats', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id')->default(0);
            $table->integer('astrologer_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->text('message')->nullable();
            $table->enum('message_by', ['user', 'astrologer'])->default('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_astrologer_appointment_chats');
    }
};
