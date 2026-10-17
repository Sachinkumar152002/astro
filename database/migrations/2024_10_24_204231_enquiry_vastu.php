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
      Schema::create('enquiry_vastus', function (Blueprint $table){
        $table->increments('id');
        $table->integer('user_id')->default(0);
        $table->string('name')->nullable();
        $table->string('email')->nullable();
        $table->integer('phone')->nullable();
        $table->string('country')->nullable();
        $table->string('state')->nullable();
        $table->string('city')->nullable();
        $table->integer('pincode')->nullable();
        $table->integer('service_id')->nullable();
        $table->string('service_name')->nullable();
        $table->string('service_type')->nullable();
        $table->string('file')->nullable();
        $table->string('length')->nullable();
        $table->string('breadth')->nullable();
        $table->text('message')->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_vastus');
    }
};
