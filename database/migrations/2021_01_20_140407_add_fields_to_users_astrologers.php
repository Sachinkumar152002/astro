<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersAstrologers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('gender')->default(0);
            $table->string('register_from_ip')->nullable();
            $table->integer('language_id')->default(0);
            $table->integer('loyality_points')->default(0);
        });
        Schema::table('astrologers', function (Blueprint $table) {
            $table->tinyInteger('gender')->default(0);
            $table->string('register_from_ip')->nullable();
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
            $table->dropColumn('gender');
            $table->dropColumn('register_from_ip');
            $table->dropColumn('language_id');
            $table->dropColumn('loyality_points');
        });
        Schema::table('astrologers', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('register_from_ip');
        });
    }
}
