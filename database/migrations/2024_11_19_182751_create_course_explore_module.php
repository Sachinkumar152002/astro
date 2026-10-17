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
        Schema::create('course_explore_modules', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('user_type');
            $table->integer('course_id');
            $table->integer('module_id');
            $table->integer('passed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_explore_modules');
    }
};
