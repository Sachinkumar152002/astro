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
        Schema::table('astrologer_lives', function (Blueprint $table) {
          $table->datetime("leaved_at")->nullable()->after("end_at");
          $table->datetime("joined_at")->nullable()->after("end_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_lives', function (Blueprint $table) {
          $table->dropColumn("leaved_at");
          $table->dropColumn("joined_at");
        });
    }
};
