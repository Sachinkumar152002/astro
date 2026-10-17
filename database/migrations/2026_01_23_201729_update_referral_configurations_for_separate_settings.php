<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_configurations', function (Blueprint $table) {
            // Drop old columns that will be replaced
            $table->dropColumn(['applicable_user_types', 'reward_amount', 'reward_trigger', 'max_referrals_per_user', 'max_total_earning', 'reward_expiry_days']);
        });

        Schema::table('referral_configurations', function (Blueprint $table) {
            // Astrologer to Astrologer settings
            $table->decimal('astrologer_to_astrologer_reward_amount', 10, 2)->default(0)->after('enable_app_referral');
            $table->enum('astrologer_to_astrologer_trigger', ['signup', 'account_verify', 'first_consultation', 'min_consultation_amount'])->default('signup')->after('astrologer_to_astrologer_reward_amount');
            $table->decimal('astrologer_to_astrologer_min_consultation_amount', 10, 2)->nullable()->after('astrologer_to_astrologer_trigger');
            $table->integer('astrologer_to_astrologer_max_referrals')->nullable()->after('astrologer_to_astrologer_min_consultation_amount');
            $table->decimal('astrologer_to_astrologer_max_earning', 10, 2)->nullable()->after('astrologer_to_astrologer_max_referrals');
            $table->integer('astrologer_to_astrologer_expiry_days')->nullable()->after('astrologer_to_astrologer_max_earning');
            $table->tinyInteger('astrologer_to_astrologer_status')->default(1)->after('astrologer_to_astrologer_expiry_days');

            // Astrologer to Customer settings
            $table->decimal('astrologer_to_customer_reward_amount', 10, 2)->default(0)->after('astrologer_to_astrologer_status');
            $table->enum('astrologer_to_customer_trigger', ['signup', 'first_purchase'])->default('signup')->after('astrologer_to_customer_reward_amount');
            $table->decimal('astrologer_to_customer_min_purchase_amount', 10, 2)->nullable()->after('astrologer_to_customer_trigger');
            $table->integer('astrologer_to_customer_max_referrals')->nullable()->after('astrologer_to_customer_min_purchase_amount');
            $table->decimal('astrologer_to_customer_max_earning', 10, 2)->nullable()->after('astrologer_to_customer_max_referrals');
            $table->integer('astrologer_to_customer_expiry_days')->nullable()->after('astrologer_to_customer_max_earning');
            $table->tinyInteger('astrologer_to_customer_status')->default(1)->after('astrologer_to_customer_expiry_days');

            // Customer settings
            $table->decimal('customer_reward_amount', 10, 2)->default(0)->after('astrologer_to_customer_status');
            $table->enum('customer_trigger', ['signup', 'first_purchase'])->default('signup')->after('customer_reward_amount');
            $table->decimal('customer_min_purchase_amount', 10, 2)->nullable()->after('customer_trigger');
            $table->integer('customer_max_referrals')->nullable()->after('customer_min_purchase_amount');
            $table->decimal('customer_max_earning', 10, 2)->nullable()->after('customer_max_referrals');
            $table->integer('customer_expiry_days')->nullable()->after('customer_max_earning');
            $table->tinyInteger('customer_status')->default(1)->after('customer_expiry_days');
        });
    }

    public function down(): void
    {
        Schema::table('referral_configurations', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'astrologer_to_astrologer_reward_amount',
                'astrologer_to_astrologer_trigger',
                'astrologer_to_astrologer_min_consultation_amount',
                'astrologer_to_astrologer_max_referrals',
                'astrologer_to_astrologer_max_earning',
                'astrologer_to_astrologer_expiry_days',
                'astrologer_to_astrologer_status',
                'astrologer_to_customer_reward_amount',
                'astrologer_to_customer_trigger',
                'astrologer_to_customer_min_purchase_amount',
                'astrologer_to_customer_max_referrals',
                'astrologer_to_customer_max_earning',
                'astrologer_to_customer_expiry_days',
                'astrologer_to_customer_status',
                'customer_reward_amount',
                'customer_trigger',
                'customer_min_purchase_amount',
                'customer_max_referrals',
                'customer_max_earning',
                'customer_expiry_days',
                'customer_status'
            ]);
        });

        Schema::table('referral_configurations', function (Blueprint $table) {
            // Restore old columns
            $table->json('applicable_user_types')->nullable()->comment('["user", "astrologer"]');
            $table->decimal('reward_amount', 10, 2)->default(0);
            $table->enum('reward_trigger', ['signup', 'first_purchase'])->default('signup');
            $table->integer('max_referrals_per_user')->nullable();
            $table->decimal('max_total_earning', 10, 2)->nullable();
            $table->integer('reward_expiry_days')->nullable();
        });
    }
};
