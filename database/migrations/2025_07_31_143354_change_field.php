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
        Schema::table('horoscope_dailies', function (Blueprint $table) {
          $table->longText('api_response')->nullable()->change();
        });
        Schema::table('horoscope_monthlies', function (Blueprint $table) {
          $table->longText('api_response')->nullable()->change();
        });
        Schema::table('horoscope_weeklies', function (Blueprint $table) {
          $table->longText('api_response')->nullable()->change();
        });
        Schema::table('horoscope_yearlies', function (Blueprint $table) {
          $table->longText('api_response')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horoscope_yearlies', function (Blueprint $table) {
            //
        });
    }
};
