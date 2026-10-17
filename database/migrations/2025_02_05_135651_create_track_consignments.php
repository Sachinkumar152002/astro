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
        Schema::create('track_consignments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->default(0);
            $table->integer('consignment_id')->default(0);
            $table->string('waybill')->nullable();
            $table->string('status')->nullable();
            $table->string('status_code')->nullable();
            $table->string('status_location')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('instruction')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_consignments');
    }
};
