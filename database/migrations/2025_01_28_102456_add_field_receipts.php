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
        $table->string('relation_type')->nullable()->comment('1->order, 2->recharge')->after('invoice_payment_date_time');
        $table->integer('relation_id')->default(0)->after('invoice_payment_date_time');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('receipts', function (Blueprint $table){
        $table->dropColumn('relation_id');
        $table->dropColumn('relation_type');
      });
    }
};
