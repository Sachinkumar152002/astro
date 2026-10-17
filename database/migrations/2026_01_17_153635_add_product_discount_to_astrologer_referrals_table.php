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
            $table->decimal('product_discount', 5, 2)->default(0)->after('user_reward');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_referrals', function (Blueprint $table) {
            $table->dropColumn('product_discount');
        });
    }
};
