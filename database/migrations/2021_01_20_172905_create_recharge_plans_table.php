<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->double('amount', 10, 2)->default(0);
            $table->double('special_amount', 10, 2)->default(0);
            $table->double('talk_amount', 10, 2)->default(0);
            $table->integer('loyality_points')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('one_time')->default(0);
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
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
        Schema::dropIfExists('recharge_plans');
    }
}
