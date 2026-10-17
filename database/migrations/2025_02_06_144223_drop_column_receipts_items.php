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
      Schema::table('receipt_items', function (Blueprint $table){
        $table->dropColumn('invoice_id');
        $table->dropColumn('invoice_prefix');
        $table->dropColumn('invoice_number');
        $table->dropColumn('invoice_item_id');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('receipt_items', function (Blueprint $table){
        $table->dropColumn('invoice_id');
        $table->dropColumn('invoice_prefix');
        $table->dropColumn('invoice_number');
        $table->dropColumn('invoice_item_id');
      });
    }
};
