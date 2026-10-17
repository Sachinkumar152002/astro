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
      Schema::create('shipping_address', function (Blueprint $table){
        $table->increments('id');
        $table->integer('user_id')->nullable();
        $table->string('user_type')->nullable();
        $table->string('address')->nullable();
        $table->integer('country_id')->nullable();
        $table->string('country')->nullable();
        $table->integer('state_id')->nullable();
        $table->string('state')->nullable();
        $table->integer('city_id')->nullable();
        $table->string('city')->nullable();
        $table->integer('zipcode')->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_address');
    }
};
