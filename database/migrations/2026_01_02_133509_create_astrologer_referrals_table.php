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
        Schema::create('astrologer_referrals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('astrologer_id');
            $table->string('referral_code', 10)->unique();
            $table->decimal('astrologer_reward', 10, 2)->default(0)->comment('Amount astrologer gets when referral code is used');
            $table->decimal('user_reward', 10, 2)->default(0)->comment('Amount user gets when using referral code');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('astrologer_id')->references('id')->on('astrologers')->onDelete('cascade');
            $table->index('referral_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_referrals');
    }
};
