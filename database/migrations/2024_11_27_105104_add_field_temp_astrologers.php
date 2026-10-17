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
       Schema::table('temp_astrologers', function(Blueprint $table){
         $table->integer('image_id')->nullable();
         $table->string('ship_address')->nullable();
         $table->integer('ship_country_id')->nullable();
         $table->integer('ship_state_id')->nullable();
         $table->integer('ship_city_id')->nullable();
         $table->integer('ship_zip_code')->nullable();
         $table->string('days')->nullable();
         $table->integer('pan_id')->nullable();
         $table->integer('passbook_id')->nullable();
         $table->integer('id_proof')->nullable();
         $table->integer('address_proof')->nullable();
         $table->time('birth_time')->nullable();
         $table->string('birth_place')->nullable();
       });
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
       Schema::table('temp_astrologers', function(Blueprint $table){
         $table->dropColumn('image_id');
         $table->dropColumn('ship_address');
         $table->dropColumn('ship_country_id');
         $table->dropColumn('ship_state_id');
         $table->dropColumn('ship_city_id');
         $table->dropColumn('ship_zip_code');
         $table->dropColumn('days');
         $table->dropColumn('pan_id');
         $table->dropColumn('passbook_id');
         $table->dropColumn('id_proof');
         $table->dropColumn('address_proof');
         $table->dropColumn('birth_time');
         $table->dropColumn('birth_place');
       });
     }
};
