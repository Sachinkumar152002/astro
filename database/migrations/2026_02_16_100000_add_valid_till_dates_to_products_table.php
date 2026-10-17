<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Multiple valid till dates for puja (e.g. 16, 18, 20, 21 of a month/year).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('valid_till_dates')->nullable()->after('expire_at')->comment('Array of Y-m-d dates when puja is valid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('valid_till_dates');
        });
    }
};
