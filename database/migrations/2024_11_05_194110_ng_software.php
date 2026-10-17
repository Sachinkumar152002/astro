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
      Schema::create('ng_softwares', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('image_id')->default(0);
        $table->string('title')->nullable();
        $table->text('description')->nullable();
        $table->string('slug')->nullable();
        $table->string('meta_title')->nullable();
        $table->string('meta_description')->nullable();
        $table->string('meta_keywords')->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ng_softwares');
    }
};
