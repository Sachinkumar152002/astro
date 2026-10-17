<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->default(0);
            $table->string('name')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('total_time')->default(0);
            $table->integer('questions')->default(0);
            $table->integer('minus_marks')->default(0);
            $table->integer('passing_marks')->default(0);
            $table->integer('attempt_allow')->default(1);
            $table->text('details')->nullable();
            $table->tinyInteger('instant_result')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_modules');
    }
}
