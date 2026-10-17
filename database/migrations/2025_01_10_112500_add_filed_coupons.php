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
      Schema::table('coupons', function (Blueprint $table){
        $table->integer('only_one_time')->nullable();
        $table->decimal('minimum_amount', 10, 2)->default(0);
        $table->decimal('amount', 10, 2)->change()->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('coupons', function (Blueprint $table){
        $table->dropColumn('only_one_time');
        $table->dropColumn('minimum_amount');
        $table->decimal('amount', 10, 2)->change()->default(0);
      });
    }
};
