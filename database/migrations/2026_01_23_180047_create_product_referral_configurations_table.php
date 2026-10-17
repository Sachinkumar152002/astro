<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_referral_configurations', function (Blueprint $table) {
            $table->id();
            $table->boolean('enable_product_referral')->default(false);
            $table->json('applicable_user_types')->nullable()->comment('["user", "astrologer"]');
            $table->enum('commission_mode', ['PERCENTAGE', 'FIXED'])->default('PERCENTAGE');
            $table->enum('trigger', ['payment_success'])->default('payment_success');
            $table->enum('credit_time', ['instant', 'after_completion'])->default('instant');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_referral_configurations');
    }
};
