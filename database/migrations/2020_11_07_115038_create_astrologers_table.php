<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstrologersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('astrologers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('display_name', 50)->nullable();
            $table->string('email');
            $table->string('password');
            $table->string('phone', 20);
            $table->string('phone_code', 10);
            $table->string('image_id', 10)->nullable();
            $table->longText('about')->nullable();
            $table->decimal('price', 8, 2)->default(0.0);
            $table->decimal('special_price', 8, 2)->default(0.0);
            $table->string('sp_from_date', 10)->nullable();
            $table->string('sp_to_date', 10)->nullable();
            $table->mediumText('time_availability')->nullable();
            $table->string('job_start_from', 10)->nullable();
            $table->string('address', 50)->nullable();
            $table->string('city_id', 10)->nullable();
            $table->string('state_id', 10)->nullable();
            $table->string('country_id', 10)->nullable();
            $table->string('pincode', 20)->nullable();
            $table->tinyInteger('chat')->default(0);
            $table->tinyInteger('call')->default(0);
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
        Schema::dropIfExists('astrologers');
    }
}
