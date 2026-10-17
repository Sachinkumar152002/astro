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
      Schema::table('receipts', function (Blueprint $table){
        $table->integer('type')->default(0)->change()->comment('0->order, 1->cancel, 2->return');
        $table->dropColumn('invoice_id');
        $table->dropColumn('invoice_prefix');
        $table->dropColumn('invoice_number');
        $table->dropColumn('invoice_payment_id');
        $table->dropColumn('invoice_payment_method');
        $table->dropColumn('invoice_payment_date_time');
        $table->dropColumn('relation_id');
        $table->dropColumn('relation_type');
        $table->dropColumn('payment_method');
        $table->dropColumn('payment_date_time');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('receipts', function (Blueprint $table){
        $table->integer('type')->default(0)->change()->comment('0->order, 1->cancel, 2->return');
        $table->dropColumn('invoice_id');
        $table->dropColumn('invoice_prefix');
        $table->dropColumn('invoice_number');
        $table->dropColumn('invoice_payment_id');
        $table->dropColumn('invoice_payment_method');
        $table->dropColumn('invoice_payment_date_time');
        $table->dropColumn('relation_id');
        $table->dropColumn('relation_type');
        $table->dropColumn('payment_method');
        $table->dropColumn('payment_date_time');
      });
    }
};
