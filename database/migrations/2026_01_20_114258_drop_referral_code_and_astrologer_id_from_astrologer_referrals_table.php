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
        Schema::table('astrologer_referrals', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['astrologer_id']);
            // Drop index on referral_code
            $table->dropIndex(['referral_code']);
            // Drop the columns
            $table->dropColumn(['referral_code', 'astrologer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_referrals', function (Blueprint $table) {
            // Add back the columns if rollback is needed
            $table->unsignedBigInteger('astrologer_id')->nullable()->after('id');
            $table->string('referral_code', 10)->nullable()->after('astrologer_id');
            // Add back index and foreign key
            $table->index('referral_code');
            $table->foreign('astrologer_id')->references('id')->on('astrologers')->onDelete('cascade');
        });
    }
};
