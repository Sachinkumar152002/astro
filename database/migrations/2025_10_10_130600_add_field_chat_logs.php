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
        Schema::table('chat_logs', function (Blueprint $table) {
          $table->integer("has_illegal_text")->default(0);
          $table->text("illegal_message")->nullable();
        });

        Schema::table('astrologer_appointments', function (Blueprint $table) {
          $table->integer("has_illegal_activity")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_logs', function (Blueprint $table) {
          $table->dropColumn("has_illegal_text");
          $table->dropColumn("illegal_message");
        });

        Schema::table('astrologer_appointments', function (Blueprint $table) {
          $table->dropColumn("has_illegal_activity");
        });
    }
};
