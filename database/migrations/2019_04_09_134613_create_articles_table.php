<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->default(0);
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('order')->default(0);
            $table->integer('user_id')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->integer('admin_id')->default(0);
            $table->string('youtube')->nullable();
            $table->integer('category_id')->default(0);
            $table->tinyInteger('trending')->default(0);
            $table->enum('lang',['en', 'hi'])->default('en');
            $table->softDeletes();
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
        Schema::dropIfExists('articles');
    }
}
