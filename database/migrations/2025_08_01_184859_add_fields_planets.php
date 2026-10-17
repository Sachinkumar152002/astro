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
        Schema::table('planets', function (Blueprint $table) {
          $table->date('date')->nullable();
          $table->time('time')->nullable();
          $table->string('lat')->nullable();
          $table->string('lon')->nullable();
          $table->string('tz')->nullable();
          $table->string('lang')->nullable();
          $table->text('response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planets', function (Blueprint $table) {
            //
        });
    }
};
