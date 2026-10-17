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
        Schema::create('course_alots', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('user_type')->nullable();
            $table->integer('course_id')->default(0);
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_alots');
    }
};
