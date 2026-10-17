<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsVerifiedToAstrologer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('astrologers', function (Blueprint $table) {
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
        Schema::table('astrologers', function (Blueprint $table) {
            $table->dropColumn('is_verified');
            $table->dropColumn('is_otp_verified');
            $table->dropColumn('last_logined');
        });
    }
}
