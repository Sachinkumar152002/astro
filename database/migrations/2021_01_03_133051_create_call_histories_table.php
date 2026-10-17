<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0);
            $table->integer('astrologer_id')->default(0);
            $table->string('from_number')->nullable();
            $table->string('to_number')->nullable();
            $table->integer('call_duration_in_sec')->default(0);
            $table->double('call_charges', 10, 2)->default(0);
            $table->double('total_charges', 10, 2)->default(0);
            $table->double('web_commission', 10, 2)->default(0);
            $table->double('commission_amount', 10, 2)->default(0);
            $table->double('astrologer_amount', 10, 2)->default(0);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
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
        Schema::dropIfExists('call_histories');
    }
}
