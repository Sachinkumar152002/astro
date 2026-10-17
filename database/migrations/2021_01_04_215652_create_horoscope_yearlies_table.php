<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoroscopeYearliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horoscope_yearlies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('zodiac_sign_id')->default(0);
            $table->integer('year')->default(0);
            $table->text('main_content')->nullable();
            $table->text('love_content')->nullable();
            $table->text('career_content')->nullable();
            $table->text('finance_content')->nullable();
            $table->text('family_content')->nullable();
            $table->text('api_response')->nullable();
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
        Schema::dropIfExists('horoscope_yearlies');
    }
}
