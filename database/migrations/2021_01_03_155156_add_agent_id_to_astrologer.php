<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgentIdToAstrologer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('astrologers', function (Blueprint $table) {
            $table->integer('agent_id')->default(0);
            $table->string('bank_account_number_verify')->nullable();
            $table->date('dob')->nullable();
            $table->double('commission', 10, 2)->nullable();
            $table->string('pan_card')->nullable();
            $table->tinyInteger('commission_type')->default(0);
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
            $table->dropColumn('agent_id');
            $table->dropColumn('bank_account_number_verify');
            $table->dropColumn('dob');
            $table->dropColumn('commission');
            $table->dropColumn('pan_card');
            $table->dropColumn('commission_type');
        });
    }
}
