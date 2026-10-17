<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Products selected here will show in Astrologer Profile under "Top Selling" with referral URL.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('show_in_astrologer_profile')->default(false);
            $table->unsignedInteger('astrologer_profile_sort_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['show_in_astrologer_profile', 'astrologer_profile_sort_order']);
        });
    }
};
