<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstrologerAppointmentPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('astrologer_appointment_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('astrologer_id')->default(0);
            $table->string('name')->nullable();
            $table->double('price', 10,2)->default(0);
            $table->integer('minutes')->default(0);
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
        Schema::dropIfExists('astrologer_appointment_packages');
    }
}
