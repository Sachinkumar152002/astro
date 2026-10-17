<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseModuleFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_module_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->default(0);
            $table->string('file')->nullable();
            $table->string('title')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_orginal_name')->nullable();
            $table->text('attributes')->nullable();
            // $table->tinyInteger('status')->default(0);
            // $table->integer('order')->default(0);
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
        Schema::dropIfExists('course_module_files');
    }
}
