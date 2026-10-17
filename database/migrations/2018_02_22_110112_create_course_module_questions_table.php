<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseModuleQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_module_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question')->nullable();
            $table->integer('course_id')->default(0);
            $table->integer('content_id')->default(0);
            $table->integer('type')->default(0);
            $table->integer('time')->default(0);
            $table->string('option_a')->nullable();
            $table->string('option_b')->nullable();
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();
            $table->integer('answer')->default(0);
            $table->integer('order')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('keywords')->nullable();
            $table->text('details')->nullable();
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
        Schema::dropIfExists('course_module_questions');
    }
}
