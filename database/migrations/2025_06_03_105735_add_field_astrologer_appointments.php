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
          $table->time('user_start_time')->nullable()->after('user_status');
          $table->time('astrologer_start_time')->nullable()->after('user_end_time');
          $table->integer('try')->default(0)->after('gender');
          $table->integer('lead_mail_sent')->default(0)->after('gender');
          $table->integer('amount_job')->default(0)->after('gender');
          $table->integer('paid')->default(0)->after('gender');
          $table->string('document')->nullable();
          $table->string('astrologer_status')->nullable()->after('max_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_appointments', function (Blueprint $table) {
          $table->dropColumn('astrologer_appointments');
          $table->dropColumn('user_start_time');
          $table->dropColumn('astrologer_start_time');
          $table->dropColumn('try');
          $table->dropColumn('lead_mail_sent');
          $table->dropColumn('amount_job');
          $table->dropColumn('paid');
          $table->dropColumn('document');
        });
    }
};
