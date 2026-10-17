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
        Schema::table('astrologer_appointments', function (Blueprint $table) {
          $table->integer("free")->default(0)->comment("0->no, 1->yes");
        });
        Schema::table('ai_astrologer_appointments', function (Blueprint $table) {
          $table->integer("free")->default(0)->comment("0->no, 1->yes");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_appointments', function (Blueprint $table) {
          $table->dropColumn("free");
        });
        Schema::table('ai_astrologer_appointments', function (Blueprint $table) {
          $table->dropColumn("free");
        });
    }
};
