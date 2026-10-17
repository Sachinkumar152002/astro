<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stores the valid date (Y-m-d) applicable at booking time for group puja orders.
     * When admin updates product valid_till_dates, existing orders keep this snapshot.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->date('group_puja_valid_date')->nullable()->after('group_puja_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('group_puja_valid_date');
        });
    }
};
