<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_configurations', function (Blueprint $table) {
            $table->id();
            $table->boolean('enable_app_referral')->default(false);
            $table->json('applicable_user_types')->nullable()->comment('["user", "astrologer"]');
            $table->decimal('reward_amount', 10, 2)->default(0);
            $table->enum('reward_trigger', ['signup', 'first_purchase'])->default('signup');
            $table->integer('max_referrals_per_user')->nullable();
            $table->decimal('max_total_earning', 10, 2)->nullable();
            $table->integer('reward_expiry_days')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_configurations');
    }
};
