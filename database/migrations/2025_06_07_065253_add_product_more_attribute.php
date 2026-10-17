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
        Schema::table('products', function (Blueprint $table) {
            $table->string('model_no')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('min_order_qty')->default(0);
            $table->integer('max_order_qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('model_no');
            $table->dropColumn('isbn');
            $table->dropColumn('min_order_qty');
            $table->dropColumn('max_order_qty');
        });
    }
};
