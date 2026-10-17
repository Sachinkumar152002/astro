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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('user_email')->nullable();
            $table->integer('astrologer_id')->default(0);
            $table->string('astrologer_email')->nullable();
            $table->string('notify_to')->nullable()->comment('user, astrologer');
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->integer('is_sent')->default(0);
            $table->string('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
