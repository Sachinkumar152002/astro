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
      Schema::table('local_pincodes', function (Blueprint $table){
        $table->string('city')->nullable();
        $table->string('post_office')->nullable();
      });
      Schema::table('domestic_pincodes', function (Blueprint $table){
        $table->string('city')->nullable();
        $table->string('post_office')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('local_pincodes', function (Blueprint $table){
        $table->dropColumn('city');
        $table->dropColumn('post_office');
      });
      Schema::table('domestic_pincodes', function (Blueprint $table){
        $table->dropColumn('city');
        $table->dropColumn('post_office');
      });
    }
};
