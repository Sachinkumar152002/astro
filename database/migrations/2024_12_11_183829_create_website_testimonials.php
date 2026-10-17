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
        Schema::create('video_testimonials', function (Blueprint $table) {
            $table->id();
            $table->integer('page')->default(0);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('city')->nullable();
            $table->string('url')->nullable();
            $table->string('video_id', 50)->nullable();
            $table->string('video_hash', 50)->nullable();
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_testimonials');
    }
};
