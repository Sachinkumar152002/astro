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
      schema::table('user_recharge_histories', function (Blueprint $table){
        $table->string('billing_phone_number')->nullabel()->change();
      });
      schema::table('orders', function (Blueprint $table){
        $table->string('billing_phone_number')->nullabel()->change();
      });
      schema::table('invoices', function (Blueprint $table){
        $table->string('phone')->nullabel()->change();
        $table->string('billing_phone_number')->nullabel()->change();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      schema::table('user_recharge_histories', function (Blueprint $table){
        $table->string('billing_phone_number')->nullabel()->change();
      });
      schema::table('orders', function (Blueprint $table){
        $table->string('billing_phone_number')->nullabel()->change();
      });
      schema::table('invoices', function (Blueprint $table){
        $table->string('phone')->nullabel()->change();
        $table->string('billing_phone_number')->nullabel()->change();
      });
    }
};
