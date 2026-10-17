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
        $table->decimal('net_total', 10, 2)->change()->default(0);
        $table->decimal('discount_amount', 10, 2)->change()->default(0);
        $table->decimal('coupon_amount', 10, 2)->change()->default(0);
        $table->decimal('igst', 10, 2)->change()->default(0);
        $table->decimal('cgst', 10, 2)->change()->default(0);
        $table->decimal('sgst', 10, 2)->change()->default(0);
        $table->decimal('sub_total', 10, 2)->change()->default(0);
        $table->decimal('total', 10, 2)->change()->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('orders', function (Blueprint $table){
        $table->decimal('net_total', 10, 2)->change()->default(0);
        $table->decimal('discount_amount', 10, 2)->change()->default(0);
        $table->decimal('coupon_amount', 10, 2)->change()->default(0);
        $table->decimal('igst', 10, 2)->change()->default(0);
        $table->decimal('cgst', 10, 2)->change()->default(0);
        $table->decimal('sgst', 10, 2)->change()->default(0);
        $table->decimal('sub_total', 10, 2)->change()->default(0);
        $table->decimal('total', 10, 2)->change()->default(0);
      });
    }
};
