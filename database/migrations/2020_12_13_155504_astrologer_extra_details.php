<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AstrologerExtraDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('astrologers', function (Blueprint $table) {
            $table->string('address_proof')->nullable();
            $table->string('id_proof')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('bank_branch')->nullable();
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
            $table->dropColumn('address_proof');
            $table->dropColumn('id_proof');
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_account_name');
            $table->dropColumn('bank_account_number');
            $table->dropColumn('bank_ifsc_code');
            $table->dropColumn('bank_branch');
        });
    }
}
