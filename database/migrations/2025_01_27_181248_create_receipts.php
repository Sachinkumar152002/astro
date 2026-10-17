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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->default(0);
            $table->string('invoice_prefix')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_payment_id')->nullable();
            $table->string('invoice_payment_method')->nullable();
            $table->string('invoice_payment_date_time')->nullable();
            $table->string('receipt_prefix')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('gst')->nullable()->default('06AABHY5236P1ZK');
            $table->string('pan')->nullable()->default('AABHY5236P');
            $table->string('bill_from_company_name')->nullable()->default('IIAG Jyotish Sansthan');
            $table->string('bill_from_company_address')->nullable()->default('H.No-490A, 3rd Floor, Sector-2, Ballabgarh, Faridabad, HARYANA-121004');
            $table->string('ship_from_company_name')->nullable()->default('IIAG Jyotish Sansthan');
            $table->string('ship_from_company_address')->nullable()->default('H.No-1887, Ground Floor, Sector-8, 7/8 Dividing Road, Near IIAG Jyotish Park, Faridabad, HARYANA-121006');
            $table->string('nature_of_supply')->nullable()->default('goods');
            $table->integer('user_id')->default(0);
            $table->string('user_type')->nullable();
            $table->string('bill_to_name')->nullable();
            $table->string('bill_to_email')->nullable();
            $table->string('bill_to_phone')->nullable();
            $table->string('bill_to_address')->nullable();
            $table->string('bill_to_city')->nullable();
            $table->string('bill_to_state')->nullable();
            $table->string('bill_to_country')->nullable();
            $table->string('bill_to_zipcode')->nullable();
            $table->string('ship_to_name')->nullable();
            $table->string('ship_to_email')->nullable();
            $table->string('ship_to_phone')->nullable();
            $table->string('ship_to_address')->nullable();
            $table->string('ship_to_city')->nullable();
            $table->string('ship_to_state')->nullable();
            $table->string('ship_to_country')->nullable();
            $table->string('ship_to_zipcode')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('coupon_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('subtotal_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->integer('total_quantity')->default(0);
            $table->decimal('igst_amount')->default(0);
            $table->decimal('cgst_amount')->default(0);
            $table->decimal('sgst_amount')->default(0);
            $table->string('payment_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_date_time')->nullable();
            $table->integer('type')->default(0)->comment('0->cancel, 1->refund');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
