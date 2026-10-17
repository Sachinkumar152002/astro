<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('referrer_id');
            $table->enum('referrer_type', ['user', 'astrologer']);
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->unsignedInteger('order_item_id')->nullable();
            $table->unsignedInteger('category_id');
            $table->decimal('order_amount', 10, 2)->default(0);
            $table->decimal('commission_rate', 10, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('max_cap_applied', 10, 2)->nullable();
            $table->enum('commission_mode', ['PERCENTAGE', 'FIXED'])->default('PERCENTAGE');
            $table->enum('credit_time', ['instant', 'after_completion'])->default('instant');
            $table->enum('status', ['pending', 'credited', 'cancelled', 'refunded'])->default('pending');
            $table->dateTime('credited_at')->nullable();
            $table->dateTime('refunded_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index(['referrer_id', 'referrer_type']);
            $table->index('order_id');
            $table->index('category_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_transactions');
    }
};
