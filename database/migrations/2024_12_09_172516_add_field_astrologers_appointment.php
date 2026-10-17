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
      Schema::table('astrologer_appointments', function (Blueprint $table){
        $table->time('count_down_end_time')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('astrologer_appointments', function (Blueprint $table){
        $table->dropColumn('count_down_end_time');
      });
    }
};
