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
      Schema::table('users', function (Blueprint $table){
        $table->string('country_code')->nullable();
        $table->string('phone_code')->nullable();
      });
      Schema::table('astrologers', function (Blueprint $table){
        $table->string('country_code')->nullable();
      });
      Schema::table('orders', function (Blueprint $table){
        $table->string('country_code')->nullable();
        $table->string('phone_code')->nullable();
      });
      Schema::table('invoices', function (Blueprint $table){
        $table->string('country_code')->nullable();
        $table->string('phone_code')->nullable();
      });
      Schema::table('user_recharge_histories', function (Blueprint $table){
        $table->string('country_code')->nullable();
        $table->string('phone_code')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('users', function (Blueprint $table){
        $table->dropColumn('country_code');
        $table->dropColumn('phone_code');
      });
      Schema::table('astrologers', function (Blueprint $table){
        $table->dropColumn('country_code');
      });
      Schema::table('orders', function (Blueprint $table){
        $table->dropColumn('country_code');
        $table->dropColumn('phone_code');
      });
      Schema::table('invoices', function (Blueprint $table){
        $table->dropColumn('country_code');
        $table->dropColumn('phone_code');
      });
      Schema::table('user_recharge_histories', function (Blueprint $table){
        $table->dropColumn('country_code');
        $table->dropColumn('phone_code');
      });
    }
};
