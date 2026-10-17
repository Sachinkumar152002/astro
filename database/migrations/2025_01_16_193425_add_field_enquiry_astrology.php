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
      Schema::table('enquiry_astrology', function (Blueprint $table){
        $table->string('gender')->nullable();
        $table->string('birth_place')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('enquiry_astrology', function (Blueprint $table){
        $table->dropColumn('gender');
        $table->dropColumn('birth_place');
      });
    }
};
