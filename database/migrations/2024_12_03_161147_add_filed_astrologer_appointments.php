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
       Schema::table('astrologer_appointments', function(Blueprint $table){
         $table->double('astrologer_amount_ratio', 10, 2)->default(0);
         $table->double('astrologer_maintainance_charge', 10, 2)->default(0);
       });
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
       Schema::table('astrologer_appointments', function(Blueprint $table){
         $table->dropColumn('astrologer_amount_ratio');
         $table->dropColumn('astrologer_maintainance_charge');
       });
     }
};
