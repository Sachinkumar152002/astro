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
        Schema::table('consignments', function (Blueprint $table) {
            $table->string('delivery_type')->nullable()->default('shiprocket')->comment('shiprocket or normal');
            $table->string('courier_company_name')->nullable();
            $table->string('awb_number')->nullable();
            $table->text('tracking_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consignments', function (Blueprint $table) {
            $table->dropColumn(['delivery_type', 'courier_company_name', 'awb_number', 'tracking_url']);
        });
    }
};
