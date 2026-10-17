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
        Schema::create('order_request_items', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('item_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_request_items');
    }
};
