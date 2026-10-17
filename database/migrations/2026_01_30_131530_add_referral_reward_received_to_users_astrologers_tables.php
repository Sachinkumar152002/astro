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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'referral_reward_received')) {
                $table->tinyInteger('referral_reward_received')->default(0)->after('used_referral_code')->comment('0=Not received, 1=Received');
            }
        });

        Schema::table('astrologers', function (Blueprint $table) {
            if (!Schema::hasColumn('astrologers', 'referral_reward_received')) {
                $table->tinyInteger('referral_reward_received')->default(0)->after('used_referral_code')->comment('0=Not received, 1=Received');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'referral_reward_received')) {
                $table->dropColumn('referral_reward_received');
            }
        });

        Schema::table('astrologers', function (Blueprint $table) {
            if (Schema::hasColumn('astrologers', 'referral_reward_received')) {
                $table->dropColumn('referral_reward_received');
            }
        });
    }
};
