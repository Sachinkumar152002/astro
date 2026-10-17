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
      Schema::create('vastu_service_packages', function (Blueprint $table){
        $table->increments('id');
        $table->integer('service_id')->default(0);
        $table->string('heading')->nullable();
        $table->string('title')->nullable();
        $table->tinyInteger('status')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vastu_service_packages');
    }
};
