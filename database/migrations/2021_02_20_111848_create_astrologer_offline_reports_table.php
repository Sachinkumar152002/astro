<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstrologerOfflineReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('astrologer_offline_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0);
            $table->integer('astrologer_id')->default(0);
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->tinyInteger('gender')->default(0);
            $table->integer('dob_dd')->default(0);
            $table->integer('dob_mm')->default(0);
            $table->integer('dob_yy')->default(0);
            $table->time('birth_time')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('services')->nullable();
            $table->string('services_total_amount')->default(0);
            $table->double('web_commission', 10, 2)->default(0);
            $table->double('commission_amount', 10, 2)->default(0);
            $table->double('astrologer_amount', 10, 2)->default(0);
            $table->tinyInteger('is_matchmaking')->default(0);
            $table->string('boy_name')->nullable();
            $table->integer('boy_dob_dd')->default(0);
            $table->integer('boy_dob_mm')->default(0);
            $table->integer('boy_dob_yy')->default(0);
            $table->time('boy_birth_time')->nullable();
            $table->string('boy_birth_place')->nullable();
            $table->string('girl_name')->nullable();
            $table->integer('girl_dob_dd')->default(0);
            $table->integer('girl_dob_mm')->default(0);
            $table->integer('girl_dob_yy')->default(0);
            $table->time('girl_birth_time')->nullable();
            $table->string('girl_birth_place')->nullable();
            $table->string('file')->nullable();
            $table->datetime('uploaded_at')->nullable();
            $table->text('astrologer_comment')->nullable();
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
        Schema::dropIfExists('astrologer_offline_reports');
    }
}
