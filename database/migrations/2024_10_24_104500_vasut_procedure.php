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
      Schema::create('vastu_procedures', function (Blueprint $table){
        $table->increments('id');
        $table->integer('image_id')->default(0);
        $table->string('title')->nullable();
        $table->text('description')->nullable();
        $table->tinyInteger('status')->default(1);
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vastu_procedures');
    }
};
