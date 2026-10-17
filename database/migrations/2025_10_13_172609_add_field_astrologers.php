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
        Schema::table('astrologers', function (Blueprint $table) {
          $table->integer("new")->default(1);
        });

        Schema::table('astrologer_appointments', function (Blueprint $table) {
          $table->decimal("discount", 10, 2)->default(0);
        });

        Schema::table('ai_astrologer_appointments', function (Blueprint $table) {
          $table->decimal("discount", 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologers', function (Blueprint $table) {
          $table->dropColumn("new");
        });

        Schema::table('astrologer_appointments', function (Blueprint $table) {
          $table->dropColumn("discount");
        });

        Schema::table('ai_astrologer_appointments', function (Blueprint $table) {
          $table->dropColumn("discount");
        });
    }
};
