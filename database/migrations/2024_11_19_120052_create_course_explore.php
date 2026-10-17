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
        Schema::create('course_explores', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('user_type');
            $table->integer('course_id');
            $table->string('course_name');
            $table->integer('module_id');
            $table->string('module_name');
            $table->integer('content_id');
            $table->string('content_name');
            $table->string('video_id');
            $table->integer('duration');
            $table->integer('watched_duration');
            $table->integer('percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_explores');
    }
};
