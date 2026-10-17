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
        Schema::table('customer_referrals', function (Blueprint $table) {
            // Enable / Disable complete app referral
            $table->boolean('is_enabled')
                ->default(0)
                ->after('status')
                ->comment('Enable app referral - 1=enabled, 0=disabled');

            // Comma separated list of client types who can participate (e.g. users, astrologers)
            $table->string('participant_types')
                ->nullable()
                ->after('is_enabled')
                ->comment('Comma separated: user,astrologer');

            // Reward type for referrer: fixed amount only for now
            $table->string('referrer_reward_type', 20)
                ->default('fixed')
                ->after('participant_types')
                ->comment('fixed, (future: percentage etc.)');

            // Referrer reward amount (alias to existing referrer_reward, kept separate for flexibility)
            $table->decimal('referrer_reward_amount', 10, 2)
                ->default(0)
                ->after('referrer_reward')
                ->comment('Configured referrer reward amount used by new UI');

            // Enable / disable separate reward for referee (new user)
            $table->boolean('has_referee_reward')
                ->default(0)
                ->after('referrer_reward_amount')
                ->comment('1 = referee (new user) reward enabled');

            // Referee reward amount
            $table->decimal('referee_reward_amount', 10, 2)
                ->default(0)
                ->after('has_referee_reward')
                ->comment('Reward amount for new user (referee)');

            // Wallet type where rewards will be credited
            $table->string('reward_wallet_type', 20)
                ->default('app_wallet')
                ->after('referee_reward_amount')
                ->comment('app_wallet / withdraw_wallet');

            // When to trigger reward
            $table->string('reward_trigger', 20)
                ->default('signup')
                ->after('reward_wallet_type')
                ->comment('signup / first_login / first_purchase');

            // Max total reward per user (referrer), 0 or null = no limit
            $table->unsignedInteger('max_reward_per_user')
                ->nullable()
                ->after('reward_trigger')
                ->comment('Maximum total reward amount per user, null = no limit');

            // Max referrals allowed per user (referrer), null = no limit
            $table->unsignedInteger('max_referrals_per_user')
                ->nullable()
                ->after('max_reward_per_user')
                ->comment('Maximum number of referrals per user, null = no limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_referrals', function (Blueprint $table) {
            $table->dropColumn([
                'is_enabled',
                'participant_types',
                'referrer_reward_type',
                'referrer_reward_amount',
                'has_referee_reward',
                'referee_reward_amount',
                'reward_wallet_type',
                'reward_trigger',
                'max_reward_per_user',
                'max_referrals_per_user',
            ]);
        });
    }
};
