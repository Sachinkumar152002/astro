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
        $table->integer('reschedule')->default(0);
        $table->integer('refund')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('astrologer_appointments', function (Blueprint $table){
        $table->dropColumn('reschedule');
        $table->dropColumn('refund');
      });
    }
};
