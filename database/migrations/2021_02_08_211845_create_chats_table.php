<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0);
            $table->integer('astrologer_id')->default(0);
            $table->string('chat_key')->nullable();
            $table->double('per_minute_charge')->nullable();
            $table->integer('max_time_in_seconds')->default(0);
            $table->integer('remaining_time_in_seconds')->default(0);
            $table->integer('chat_duration_in_sec')->default(0);
            $table->double('chat_charges', 10, 2)->default(0);
            $table->double('total_charges', 10, 2)->default(0);
            $table->double('web_commission', 10, 2)->default(0);
            $table->double('commission_amount', 10, 2)->default(0);
            $table->double('astrologer_amount', 10, 2)->default(0);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('user_status')->default(0);
            $table->integer('astrologer_status')->default(0);
            $table->integer('minutes_consumed')->default(0);
            $table->date('user_last_seen')->nullable();
            $table->date('astrologer_last_seen')->nullable();
            $table->string('user_name')->nullable();
            $table->string('astrologer_name')->nullable();
            $table->string('astrologer_image')->nullable();
            $table->string('user_image')->nullable();
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
        Schema::dropIfExists('chats');
    }
}
