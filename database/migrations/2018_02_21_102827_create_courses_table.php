<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('short_name')->nullable();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->integer('image_id')->default(0);
            $table->integer('certified_by')->default(0);
            $table->integer('duration')->default(0);
            $table->integer('access')->default(0);
            $table->double('rating',10, 2)->default(0);
            $table->double('price', 10, 2)->default(0);
            $table->double('special_price', 10, 2)->default(0);
            $table->date('special_to_date')->nullable();
            $table->date('special_from_date')->nullable();
            $table->text('ilearn')->nullable();
            $table->string('youtube')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('courses');
    }
}
