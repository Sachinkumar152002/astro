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
        Schema::create('lms_courses', function (Blueprint $table){
          $table->id();
          $table->integer('user_id')->default(0);
          $table->string('user_type', 20)->nullable();
          $table->string('user_name', 50)->nullable();
          $table->string('user_email', 50)->nullable();
          $table->string('user_phone', 50)->nullable();
          $table->integer('order_id')->default(0);
          $table->integer('order_item_id')->default(0);
          $table->integer('course_id')->default(0);
          $table->string('course_name', 50)->nullable();
          $table->date('expire_date')->nullable();
          $table->string('certificate')->nullable();
          $table->integer('status')->default(0);
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_courses');
    }
};
