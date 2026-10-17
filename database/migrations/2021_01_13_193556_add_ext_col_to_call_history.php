<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtColToCallHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_histories', function (Blueprint $table) {
            $table->integer('max_minute')->default(0);
            $table->dateTime('call_start_time')->nullable();
            $table->dateTime('call_end_time')->nullable();
            $table->dateTime('call_hangup_time')->nullable();
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
            $table->dropColumn('max_minute');
            $table->dropColumn('call_start_time');
            $table->dropColumn('call_end_time');
            $table->dropColumn('call_hangup_time');
        });
    }
}
