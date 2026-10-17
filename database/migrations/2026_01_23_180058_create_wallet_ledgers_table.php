<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_ledgers', function (Blueprint $table) {
            $table->id();
            $table->enum('wallet_type', ['referral', 'commission']);
            $table->unsignedInteger('user_id');
            $table->enum('user_type', ['user', 'astrologer']);
            $table->enum('transaction_type', ['credit', 'debit']);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('balance_after', 10, 2)->default(0);
            $table->enum('reference_type', ['referral_transaction', 'commission_transaction', 'withdrawal', 'adjustment']);
            $table->unsignedInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['wallet_type', 'user_id', 'user_type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_ledgers');
    }
};
