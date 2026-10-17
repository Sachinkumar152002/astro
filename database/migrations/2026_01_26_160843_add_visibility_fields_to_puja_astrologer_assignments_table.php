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
        Schema::table('puja_astrologer_assignments', function (Blueprint $table) {
            $table->boolean('display_in_user_profile')->default(0)->after('notes');
            $table->boolean('display_in_astrologer_profile')->default(0)->after('display_in_user_profile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('puja_astrologer_assignments', function (Blueprint $table) {
            $table->dropColumn(['display_in_user_profile', 'display_in_astrologer_profile']);
        });
    }
};
