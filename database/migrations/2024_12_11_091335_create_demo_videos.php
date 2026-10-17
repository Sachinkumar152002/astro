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
        Schema::create('demo_videos', function (Blueprint $table) {
            $table->id();
            $table->integer('page_id')->default(0);
            $table->string('platform_type')->nullable();
            $table->string('url')->nullable();
            $table->string('video_id', 50)->nullable();
            $table->string('video_hash')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('status')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_videos');
    }
};
