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
        Schema::create('receipt_items', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->default(0);
            $table->string('invoice_prefix')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_payment_id')->nullable();
            $table->string('invoice_payment_method')->nullable();
            $table->string('invoice_payment_date_time')->nullable();
            $table->integer('invoice_item_id')->default(0);
            $table->integer('receipt_id')->default(0);
            $table->string('receipt_prefix')->nullable();
            $table->string('receipt_number')->nullable();
            $table->integer('user_id')->default(0);
            $table->string('user_type')->nullable();
            $table->string('item_name')->nullable();
            $table->string('item_description')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('coupon_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('subtotal_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('tax_type')->nullable();
            $table->decimal('igst_percentage', 10, 2)->nullable();
            $table->decimal('igst_amount', 10, 2)->nullable();
            $table->decimal('cgst_percentage', 10, 2)->nullable();
            $table->decimal('cgst_amount', 10, 2)->nullable();
            $table->decimal('sgst_percentage', 10, 2)->nullable();
            $table->decimal('sgst_amount', 10, 2)->nullable();
            $table->string('hsn_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_items');
    }
};
