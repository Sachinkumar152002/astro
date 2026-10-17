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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('prefix', 20)->nullable();
            $table->string('number', 10)->default(0);
            $table->integer('relation_id')->default(0);
            $table->string('relation_type', 20)->nullable();
            $table->integer('user_id')->default(0);
            $table->string('user_type', 20)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->integer('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 20)->nullable();
            $table->string('state', 20)->nullable();
            $table->string('country', 20)->nullable();
            $table->text('description')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->double('sgst', 10, 2)->nullable();
            $table->double('igst', 10, 2)->nullable();
            $table->double('cgst', 10, 2)->nullable();
            $table->double('amount', 10, 2)->nullable();
            $table->double('total_amount', 10, 2)->nullable();
            $table->double('discount_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
