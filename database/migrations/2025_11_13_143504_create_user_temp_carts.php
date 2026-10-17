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
        Schema::create('user_temp_carts', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->default(0);
            $table->string("user_type")->default(0);
            $table->decimal("sub_total_amount",10,2)->default(0);
            $table->decimal("discount_amount",10,2)->default(0);
            $table->decimal("extra_discount_amount",10,2)->default(0);
            $table->decimal("igst_amount",10,2)->default(0);
            $table->decimal("cgst_amount",10,2)->default(0);
            $table->decimal("sgst_amount",10,2)->default(0);
            $table->decimal("gst_tax_amount",10,2)->default(0);
            $table->decimal("shipping_amount",10,2)->default(0);
            $table->decimal("total_amount",10,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_temp_carts');
    }
};
