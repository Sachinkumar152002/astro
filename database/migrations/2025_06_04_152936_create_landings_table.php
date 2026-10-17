<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * *8
     * @return void
     */
    public function up()
    {
        Schema::create('landings', function (Blueprint $table) {
            $table->id(); // id bigint unsigned AUTO_INCREMENT PRIMARY KEY
            $table->string('title', 512)->nullable();
            $table->string('slug', 512)->nullable();
            $table->text('style')->nullable();
            $table->text('header_script')->nullable();
            $table->text('content')->nullable();
            $table->text('footer_script')->nullable();
            $table->string('meta_title', 512)->nullable();
            $table->longText('html')->nullable();
            $table->longText('css')->nullable();
            $table->longText('components')->nullable();
            $table->longText('styles')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('status')->default(0);
            $table->integer('show_in_navigation')->default(0);
            $table->timestamps(); // created_at and updated_at
            $table->integer('icon_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('landings');
    }
}
