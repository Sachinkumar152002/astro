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
      Schema::table('invoices', function (Blueprint $table){
        $table->string('billing_full_name')->nullable();
        $table->string('billing_email')->nullable();
        $table->integer('billing_phone_number')->nullable();
        $table->string('billing_address')->nullable();
        $table->string('billing_country')->nullable();
        $table->string('billing_state')->nullable();
        $table->string('billing_city')->nullable();
        $table->string('billing_zipcode')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('invoices', function (Blueprint $table){
        $table->dropColumn('billing_full_name');
        $table->dropColumn('billing_email');
        $table->dropColumn('billing_phone_number');
        $table->dropColumn('billing_address');
        $table->dropColumn('billing_country');
        $table->dropColumn('billing_state');
        $table->dropColumn('billing_city');
        $table->dropColumn('billing_zipcode');
      });
    }
};
