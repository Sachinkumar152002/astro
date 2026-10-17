<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAstrologerFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('astrologers', function (Blueprint $table) {
            $table->integer('image_id')->default(0)->change();
            $table->integer('city_id')->default(0)->change();
            $table->integer('state_id')->default(0)->change();
            $table->integer('country_id')->default(0)->change();
            $table->date('sp_from_date')->nullable()->change();
            $table->date('sp_to_date')->nullable()->change();
            $table->date('job_start_from')->nullable()->change();
            $table->dropColumn('time_availability');
            $table->double('service_charge', 10,2)->default(0);
        });
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('astrologers', function (Blueprint $table) {
            $table->string('image_id', 10)->nullable()->change();
            $table->string('city_id', 10)->nullable()->change();
            $table->string('state_id', 10)->nullable()->change();
            $table->string('country_id', 10)->nullable()->change();
            $table->string('sp_from_date', 10)->nullable()->change();
            $table->string('sp_to_date', 10)->change();
            $table->string('job_start_from', 10)->nullable()->change();
            $table->mediumText('time_availability')->nullable();
            $table->dropColumn('service_charge');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
