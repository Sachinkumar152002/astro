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
        Schema::create('astrologer_live_user_chats', function (Blueprint $table) {
            $table->id();
            $table->integer('astrologer_live_id')->default(0);
            $table->integer('astrologer_live_user_id')->default(0);
            $table->integer('astrologer_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_live_user_chat');
    }
};
