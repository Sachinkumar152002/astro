<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDobToUsersAstrologers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('dob_dd')->default(0);
            $table->integer('dob_mm')->default(0);
            $table->integer('dob_yyyy')->default(0);
            $table->dateTime('deleted_at')->nullable();
        });
        Schema::table('astrologers', function (Blueprint $table) {
            $table->integer('dob_dd')->default(0);
            $table->integer('dob_mm')->default(0);
            $table->integer('dob_yyyy')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('job_start_year')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dob_dd');
            $table->dropColumn('dob_mm');
            $table->dropColumn('dob_yyyy');
            $table->dropColumn('deleted_at');
        });
        Schema::table('astrologers', function (Blueprint $table) {
            $table->dropColumn('dob_dd');
            $table->dropColumn('dob_mm');
            $table->dropColumn('dob_yyyy');
            $table->dropColumn('deleted_at');
            $table->dropColumn('job_start_year');
        });
    }
}
