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
      Schema::table('cart_items', function (Blueprint $table){
        $table->double('price', 10, 2)->change()->default(0);
        $table->double('unit_price', 10, 2)->change()->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('cart_items', function (Blueprint $table){
        $table->integer('price')->change()->default(0);
        $table->integer('unit_price')->change()->default(0);
      });
    }
};
