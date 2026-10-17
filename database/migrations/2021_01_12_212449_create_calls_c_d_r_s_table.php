<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallsCDRSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls_c_d_r_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('call_id')->nullable();
            $table->string('uuid')->nullable();
            $table->string('direction')->nullable();
            $table->string('description')->nullable();
            $table->string('detailed_description')->nullable();
            $table->string('status')->nullable();
            $table->string('blocked_number_id')->nullable();
            $table->string('recording_url')->nullable();
            $table->string('service')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->dateTime('end_stamp')->nullable();
            $table->string('broadcast_id')->nullable();
            $table->string('dtmf_input')->nullable();
            $table->integer('call_duration')->default(0);
            $table->integer('answered_seconds')->default(0);
            $table->integer('minutes_consumed')->default(0);
            $table->double('charges', 10, 2)->default(0);
            $table->string('department_name')->nullable();
            $table->string('agent_number')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('client_number')->nullable();
            $table->string('did_number')->nullable();
            $table->string('reason')->nullable();
            $table->string('hangup_cause')->nullable();
            $table->string('notes')->nullable();
            $table->string('contact_details')->nullable();
            $table->string('missed_agents')->nullable();
            $table->text('call_flow')->nullable();
            $table->string('accountid')->nullable();
            $table->string('agent_ring_time')->nullable();
            $table->string('circle')->nullable();
            $table->string('agent_hangup_data')->nullable();
            $table->string('transfer_missed_agent')->nullable();
            $table->string('customer_ring_time')->nullable();
            $table->string('total_hold_duration')->nullable();
            $table->string('hold_count')->nullable();
            $table->string('disposition_name')->nullable();
            $table->string('disposition_code')->nullable();
            $table->tinyInteger('is_calc')->defult(0);
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
        Schema::dropIfExists('calls_c_d_r_s');
    }
}
