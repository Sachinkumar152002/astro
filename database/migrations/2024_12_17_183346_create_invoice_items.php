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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->default(0);
            $table->string('invoice_prefix', 20)->nullable();
            $table->string('invoice_number', 10)->default(0);
            $table->integer('relation_id')->default(0);
            $table->string('relation_type', 20)->nullable();
            $table->integer('user_id')->default(0);
            $table->string('user_type', 20)->nullable();
            $table->string('item_name', 50)->nullable();
            $table->text('item_description')->nullable();
            $table->integer('quantity')->default(0);
            $table->double('amount', 10, 2)->nullable();
            $table->double('total_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
