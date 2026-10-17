<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_calls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->string('client_id')->nullable();
            $table->string('call_id')->nullable();
            $table->tinyInteger('direction')->default(0);
            $table->string('source')->nullable();
            $table->string('type')->nullable();
            $table->string('did')->nullable();
            $table->string('multiple_destination_type')->nullable();
            $table->string('multiple_destination_name')->nullable();
            $table->string('destination')->nullable();
            $table->string('state')->nullable();
            $table->string('queue_state')->nullable();
            $table->string('channel_id')->nullable();
            $table->string('sip_domain')->nullable();
            $table->string('broadcast_id')->nullable();
            $table->string('broadcast_no')->nullable();
            $table->string('call_time')->nullable();
            $table->string('agent_name')->nullable();
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
        Schema::dropIfExists('live_calls');
    }
}
