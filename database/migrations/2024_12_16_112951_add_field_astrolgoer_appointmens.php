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
        $table->integer('is_reminded')->default(0);
        $table->string('reminder_time')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('astrologer_appointments', function (Blueprint $table){
        $table->dropColumn('is_reminded');
        $table->dropColumn('reminder_time');
      });
    }
};
