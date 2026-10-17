<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enquiry_vastus', function (Blueprint $table){
          $table->string('location')->nullable();
        });
        Schema::table('enquiry_cms_two', function (Blueprint $table){
          $table->string('location')->nullable();
        });
        Schema::table('users', function (Blueprint $table){
          $table->string('location')->nullable();
        });
        Schema::table('astrologers', function (Blueprint $table){
          $table->string('location')->nullable();
        });
        Schema::table('shipping_address', function (Blueprint $table){
          $table->string('location')->nullable();
        });
        Schema::table('orders', function (Blueprint $table){
          $table->string('location')->nullable()->after('state');
          $table->string('billing_location')->nullable()->after('billing_city');
        });
        Schema::table('invoices', function (Blueprint $table){
          $table->string('location')->nullable()->after('address');
          $table->string('billing_location')->nullable()->after('billing_city');
        });
        Schema::table('user_recharge_histories', function (Blueprint $table){
          $table->string('location')->nullable()->after('country');
          $table->string('billing_location')->nullable()->after('billing_city');
        });
        Schema::table('receipts', function (Blueprint $table){
          $table->string('bill_to_location')->nullable()->after('bill_to_country');
          $table->string('ship_to_location')->nullable()->after('ship_to_country');
        });
        Schema::table('temp_astrologers', function (Blueprint $table){
          $table->string('location')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiry_vastus', function (Blueprint $table){
          $table->dropColumn('location');
        });
        Schema::table('enquiry_cms_two', function (Blueprint $table){
          $table->dropColumn('location');
        });
        Schema::table('users', function (Blueprint $table){
          $table->dropColumn('location');
        });
        Schema::table('astrologers', function (Blueprint $table){
          $table->dropColumn('location');
        });
        Schema::table('shipping_address', function (Blueprint $table){
          $table->dropColumn('location');
        });
        Schema::table('orders', function (Blueprint $table){
          $table->dropColumn('location');
          $table->dropColumn('billing_location');
        });
        Schema::table('invoices', function (Blueprint $table){
          $table->dropColumn('location');
          $table->dropColumn('billing_location');
        });
        Schema::table('user_recharge_histories', function (Blueprint $table){
          $table->dropColumn('location');
          $table->dropColumn('billing_location');
        });
        Schema::table('receipts', function (Blueprint $table){
          $table->dropColumn('bill_to_location');
          $table->dropColumn('ship_to_location');
        });
        Schema::table('temp_astrologers', function (Blueprint $table){
          $table->dropColumn('location');
        });
    }
};
