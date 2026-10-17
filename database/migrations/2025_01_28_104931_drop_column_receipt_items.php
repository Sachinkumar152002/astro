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
      if(Schema::hasColumn('receipt_items', 'invoice_payment_id') && Schema::hasColumn('receipt_items', 'invoice_payment_method') && Schema::hasColumn('receipt_items', 'invoice_payment_date_time') && Schema::hasColumn('receipt_items', 'shipping_amount')){
        Schema::table('receipt_items', function (Blueprint $table){
          $table->dropColumn('invoice_payment_id');
          $table->dropColumn('invoice_payment_method');
          $table->dropColumn('invoice_payment_date_time');
          $table->dropColumn('shipping_amount');
        });
      }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
