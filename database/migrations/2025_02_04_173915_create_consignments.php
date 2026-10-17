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
        Schema::create('consignments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('weight')->default(0)->comment('gm');
            $table->integer('shipment_width')->default(0)->comment('cm');
            $table->integer('shipment_height')->default(0)->comment('cm');
            $table->integer('shipment_length')->default(0)->comment('cm');
            $table->string('shipping_mode')->default('Express');
            $table->string('status')->nullable();
            $table->string('waybill')->nullable();
            $table->text('consignment_request')->nullable();
            $table->text('consignment_response')->nullable();
            $table->time('pickup_time')->nullable();
            $table->date('pickup_date')->nullable();
            $table->string('pickup_location')->nullable();
            $table->integer('package_count')->default(1);
            $table->text('pickup_request')->nullable();
            $table->text('pickup_response')->nullable();
            $table->string('pickup_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consignments');
    }
};
