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
            // Add used_referral_code field to store the referral code used during registration
            $table->string('used_referral_code', 20)->nullable()->after('referral_code');
        });

        Schema::table('astrologers', function (Blueprint $table) {
            // Add used_referral_code field to store the referral code used during registration
            $table->string('used_referral_code', 20)->nullable()->after('referral_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('used_referral_code');
        });

        Schema::table('astrologers', function (Blueprint $table) {
            $table->dropColumn('used_referral_code');
        });
    }
};
