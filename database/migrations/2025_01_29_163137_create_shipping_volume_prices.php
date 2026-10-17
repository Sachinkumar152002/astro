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
        Schema::create('shipping_volume_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('volume_from')->default(0);
            $table->integer('volume_to')->default(0);
            $table->decimal('local_price', 10, 2)->default(0);
            $table->decimal('domestic_price', 10, 2)->default(0);
            $table->decimal('national_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_volume_prices');
    }
};
