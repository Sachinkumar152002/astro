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
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id();
            $table->integer('content_id')->default(0);
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->string('file_name')->nullable();
            $table->string('video_id')->nullable();
            $table->string('video_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            // $table->string('caption')->nullable();
            $table->json('attributes')->nullable();
            $table->integer('size')->unsigned();
            // $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_videos');
    }
};
