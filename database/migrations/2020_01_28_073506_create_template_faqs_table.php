<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_faqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('overwrite')->nullable();
            $table->string('question')->nullable();
            $table->text('answer')->nullable();
            $table->integer('order')->default(0);
            $table->integer('category_id')->default(0);
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
        Schema::dropIfExists('template_faqs');
    }
}
