<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_commissions', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['category_id']);

            // Add new foreign key to product_categories
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('category_commissions', function (Blueprint $table) {
            // Drop product_categories foreign key
            $table->dropForeign(['category_id']);

            // Restore original foreign key to categories
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }
};
