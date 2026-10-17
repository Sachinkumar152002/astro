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
      Schema::table('course_reviews', function (Blueprint $table){
        $table->double('rating', 10, 1)->change()->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('course_reviews', function (Blueprint $table){
        $table->integer('rating')->change()->default(0);
      });
    }
};
