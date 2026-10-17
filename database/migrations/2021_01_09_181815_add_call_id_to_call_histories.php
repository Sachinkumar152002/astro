<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallIdToCallHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_histories', function (Blueprint $table) {
            $table->string('call_id')->nullable();
            $table->string('service_call_id')->nullable();
            $table->string('user_status')->nullable();
            $table->string('astrologer_status')->nullable();
            $table->string('status_description')->nullable();
            $table->integer('minutes_consumed')->default(0);
            $table->string('first_response')->nullable();
            $table->text('cdr_detail')->nullable();
            $table->tinyInteger('system_call_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_histories', function (Blueprint $table) {
            $table->dropColumn('call_id');
            $table->dropColumn('service_call_id');
            $table->dropColumn('user_status');
            $table->dropColumn('astrologer_status');
            $table->dropColumn('status_description');
            $table->dropColumn('minutes_consumed');
            $table->dropColumn('first_response');
            $table->dropColumn('cdr_detail');
            $table->dropColumn('system_call_status');
        });
    }
}
