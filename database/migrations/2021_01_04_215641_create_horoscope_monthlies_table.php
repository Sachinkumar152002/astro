<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoroscopeMonthliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horoscope_monthlies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('zodiac_sign_id')->default(0);
            $table->integer('month_number')->default(0);
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->text('main_content')->nullable();
            $table->text('love_content')->nullable();
            $table->text('career_content')->nullable();
            $table->text('finance_content')->nullable();
            $table->text('health_content')->nullable();
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
        Schema::dropIfExists('horoscope_monthlies');
    }
}
