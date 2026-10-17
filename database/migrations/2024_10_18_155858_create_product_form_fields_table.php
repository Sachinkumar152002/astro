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
        Schema::create('product_form_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->integer('order')->nullable();
            $table->string('form_field')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_form_fields');
    }
};
