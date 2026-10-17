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
        Schema::create('course_previews', function (Blueprint $table) {
            $table->id();
            $table->integer('content_id')->nullable();
            $table->string('type')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_id')->nullable();
            $table->string('title')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->enum('category', ['daily', 'weekly', 'yearly'])->default('daily');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_previews');
    }
};
