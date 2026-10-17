<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
            $table->integer('last_otp')->default(0);
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('is_otp_verified')->default(0);
            $table->timestamp('last_logined')->nullable();
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
            $table->dropColumn('status');
            $table->dropColumn('last_otp');
            $table->dropColumn('is_verified');
            $table->dropColumn('is_otp_verified');
            $table->dropColumn('last_logined');
        });
    }
}
