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
       Schema::table('astrologers', function(Blueprint $table){
         $table->integer('passbook_id')->nullable();
         $table->time('birth_time')->nullable();
         $table->string('birth_place')->nullable();
         $table->double('wallet', 10, 2)->default(0);
       });
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
       Schema::table('astrologers', function(Blueprint $table){
         $table->dropColumn('passbook_id');
         $table->dropColumn('birth_time');
         $table->dropColumn('birth_place');
         $table->dropColumn('wallet');
       });
     }
};
