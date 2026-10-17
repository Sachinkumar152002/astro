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
        Schema::create('admin_side_cart_items', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->default(0);
            $table->string("user_type")->nullable();
            $table->integer("item_id")->default(0);
            $table->string("item_type")->nullable();
            $table->integer("quantity")->default(0);
            $table->decimal("base_price",10,2)->default(0);
            $table->decimal("sale_price",10,2)->default(0);
            $table->decimal("unit_base_price",10,2)->default(0);
            $table->decimal("unit_sale_price",10,2)->default(0);
            $table->longText("formdata")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_side_cart_items');
    }
};
