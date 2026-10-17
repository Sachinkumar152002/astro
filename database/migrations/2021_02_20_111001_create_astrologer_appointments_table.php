<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstrologerAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('astrologer_appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('package_id')->default(0);
            $table->string('package')->nullable();
            $table->double('package_price', 10,2)->default(0);//
            $table->integer('package_minutes')->default(0);//
            $table->date('call_to_date')->nullable();//
            $table->time('call_to_time')->nullable();//
            $table->text('message')->nullable();
            $table->integer('from_user_id')->default(0);
            $table->integer('to_astrologer_id')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('comments')->nullable();
            $table->double('web_commission', 10,2)->default(0);//
            $table->double('commission_amount', 10, 2)->default(0);//
            $table->double('astrologer_amount', 10, 2)->default(0);
            $table->integer('call_history_id')->default(0);
            $table->tinyInteger('call_status')->default(0);
            $table->timestamps();
        });
        Schema::table('call_histories', function (Blueprint $table) {
            $table->tinyInteger('is_appointment_call')->default(0);
            $table->integer('appointment_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('astrologer_appointments');
        Schema::table('call_histories', function (Blueprint $table) {
            $table->dropColumn('is_appointment_call');
            $table->dropColumn('appointment_id');
        });
    }
}
