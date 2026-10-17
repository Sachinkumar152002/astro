<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('astrologer_id')->constrained('astrologers')->onDelete('cascade');
            $table->enum('wallet_type', ['referral', 'commission', 'both'])->default('both');
            $table->decimal('referral_amount', 10, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'processed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();

            $table->index('astrologer_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_withdrawals');
    }
};
