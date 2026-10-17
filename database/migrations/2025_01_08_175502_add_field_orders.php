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
      Schema::table('orders', function (Blueprint $table){
        $table->string('payment_id')->nullable();
        $table->string('payment_method')->nullable();
        $table->string('payment_date_time')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('orders', function (Blueprint $table){
        $table->dropColumn('payment_id');
        $table->dropColumn('payment_method');
        $table->dropColumn('payment_date_time');
      });
    }
};
