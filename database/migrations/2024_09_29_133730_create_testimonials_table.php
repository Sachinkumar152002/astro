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
        Schema::create('testimonials', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('image_id')->default(0);
          $table->string('title')->nullable();
          $table->text('description')->nullable();
          $table->tinyInteger('status')->default(0);
          $table->integer('order')->default(0);
          $table->string('video_link')->nullable();
          $table->string('name')->nullable();
          $table->softDeletes();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
