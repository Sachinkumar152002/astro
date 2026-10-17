<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRechargeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_recharge_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0);
            $table->double('amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('my_txn_id')->nullable();
            $table->string('payment_txn_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->text('payment_request')->nullable();
            $table->text('payment_response')->nullable();
            $table->string('tax')->default('GST');
            $table->string('tax_type')->default('IGST');
            $table->double('cgst', 10, 2)->default('0');
            $table->double('cgst_amount', 10, 2)->default('0');
            $table->double('sgst', 10, 2)->default('0');
            $table->double('sgst_amount', 10, 2)->default('0');
            $table->double('igst', 10, 2)->default('0');
            $table->double('igst_amount', 10, 2)->default('0');
            $table->string('coupon_code')->nullable();
            $table->double('discount', 10, 2)->default('0');
            $table->double('total_amount', 10, 2)->default('0');
            $table->double('taktime_value', 10, 2)->default('0');
            $table->double('loyality_points', 10, 2)->default('0');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode')->nullable();
            $table->string('phone')->nullable();
            $table->string('invoice_id')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->double('wallet', 10, 2)->default('0');
            $table->integer('city_id')->default(0);
            $table->integer('state_id')->default(0);
            $table->integer('country_id')->default(0);
            $table->string('pincode')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->date('birth_date')->nullable();
            $table->time('birth_time')->nullable();
            $table->string('birth_place')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_recharge_histories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('wallet');
            $table->dropColumn('city_id');
            $table->dropColumn('state_id');
            $table->dropColumn('country_id');
            $table->dropColumn('pincode');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('country');
            $table->dropColumn('birth_date');
            $table->dropColumn('birth_time');
            $table->dropColumn('birth_place');
        });
    }
}
