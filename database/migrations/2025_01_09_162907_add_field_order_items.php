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
      Schema::table('order_items', function (Blueprint $table){
        $table->string('tax_type')->nullable();
        $table->decimal('igst_percentage')->default(0);
        $table->decimal('igst_amount')->default(0);
        $table->decimal('cgst_percentage')->default(0);
        $table->decimal('cgst_amount')->default(0);
        $table->decimal('sgst_percentage')->defaul(0);
        $table->decimal('sgst_amount')->defaul(0);
        $table->integer('hsn_code')->defaul(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('order_items', function (Blueprint $table){
        $table->dropColumn('tax_type');
        $table->dropColumn('igst_percentage');
        $table->dropColumn('igst_amount');
        $table->dropColumn('cgst_percentage');
        $table->dropColumn('cgst_amount');
        $table->dropColumn('sgst_percentage');
        $table->dropColumn('sgst_amount');
        $table->dropColumn('hsn_code');
      });
    }
};
