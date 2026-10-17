<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAgentIdToVarChar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('astrologers', function (Blueprint $table) {
            $table->string('agent_id')->change();
            $table->tinyInteger('last_status')->default(0);
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
            $table->string('agent_id')->change();
            $table->dropColumn('last_status');
        });
    }
}
