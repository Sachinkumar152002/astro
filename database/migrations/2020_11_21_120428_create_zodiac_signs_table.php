<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZodiacSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zodiac_signs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('image_id')->default(0);
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->integer('sign_date_sd')->default(0);
            $table->integer('sign_date_sm')->default(0);
            $table->integer('sign_date_ed')->default(0);
            $table->integer('sign_date_em')->default(0);
            $table->string('lucky_colors')->nullable();
            $table->string('lucky_gemstones')->nullable();
            $table->string('lucky_days')->nullable();
            $table->string('symbol')->nullable();
            $table->string('lucky_number')->nullable();
            $table->string('ruiling_planet')->nullable();
            $table->string('compatible_zodiac_signs')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
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
        Schema::dropIfExists('zodiac_signs');
    }
}
