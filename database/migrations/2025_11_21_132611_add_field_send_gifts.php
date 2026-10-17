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
        Schema::table('send_gifts', function (Blueprint $table) {
          $table->integer("astrologer_live_id")->default(0)->after("astrologer_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('send_gifts', function (Blueprint $table) {
          $table->dropColumn("astrologer_live_id");
        });
    }
};
