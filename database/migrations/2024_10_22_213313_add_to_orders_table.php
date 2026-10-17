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
        Schema::table('orders', function (Blueprint $table) {
          $table->integer("coupon_id")->default(0);
          $table->double("discount_amount", 10, 2)->default(0);
          $table->double("sub_total", 10, 2)->default(0);
          $table->double("igst", 10, 2)->default(0);
          $table->double("cgst", 10, 2)->default(0);
          $table->double("sgst", 10, 2)->default(0);
          $table->double("net_total", 10, 2)->default(0);
          $table->double("total", 10, 2)->default(0);
          $table->text('payment_request')->nullable();
          $table->text('payment_response')->nullable();
          $table->string("payment_mode", 50)->nullable();
          $table->string("payment_status", 50)->nullable();
          $table->string('client_ip', 20)->nullable();
          $table->string('client_agent', 200)->nullable();
          $table->string('customer_name')->nullable();
          $table->string('customer_email')->nullable();
          $table->string('customer_phone')->nullable();
          $table->integer('order_state_code')->default(0);
          $table->string("options", 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
