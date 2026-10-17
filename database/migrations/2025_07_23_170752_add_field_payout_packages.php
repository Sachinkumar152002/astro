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
        Schema::table('payout_packages', function (Blueprint $table) {
          $table->string("commission_settlement_period",20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payout_packages', function (Blueprint $table) {
          $table->dropColumn("commission_settlement_period");
        });
    }
};
