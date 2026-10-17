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
        Schema::create('course_benifits', function (Blueprint $table) {
            $table->id();
            $table->string('course_id');
            $table->string('image_id')->nullanble();
            $table->string('title')->nullanble();
            $table->text('description')->nullanble();
            $table->string('status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_benifits');
    }
};
