<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('referrer_id');
            $table->enum('referrer_type', ['user', 'astrologer']);
            $table->unsignedInteger('referred_id');
            $table->enum('referred_type', ['user', 'astrologer']);
            $table->decimal('reward_amount', 10, 2)->default(0);
            $table->enum('reward_trigger', ['signup', 'first_purchase'])->default('signup');
            $table->enum('status', ['pending', 'credited', 'expired', 'cancelled'])->default('pending');
            $table->dateTime('expiry_date')->nullable();
            $table->dateTime('credited_at')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->timestamps();

            $table->index(['referrer_id', 'referrer_type']);
            $table->index(['referred_id', 'referred_type']);
            $table->index('status');
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_transactions');
    }
};
