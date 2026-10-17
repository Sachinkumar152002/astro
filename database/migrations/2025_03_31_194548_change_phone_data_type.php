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
        Schema::table('enquiry_astrology', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
        });
        Schema::table('enquiry_cms_two', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
        });
        Schema::table('enquiry_vastus', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('enquiry_astrology', function (Blueprint $table) {
          $table->string('phone')->nullable()->change();
      });
      Schema::table('enquiry_cms_two', function (Blueprint $table) {
          $table->string('phone')->nullable()->change();
      });
      Schema::table('enquiry_vastus', function (Blueprint $table) {
          $table->string('phone')->nullable()->change();
      });
    }
};
