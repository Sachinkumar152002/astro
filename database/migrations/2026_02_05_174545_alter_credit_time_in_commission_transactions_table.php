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
        Schema::table('commission_transactions', function (Blueprint $table) {
            // Change credit_time from enum to string to support day-based delays (delayed_1day, delayed_2day, etc.)
            $table->string('credit_time', 50)->default('instant')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_transactions', function (Blueprint $table) {
            // Revert back to enum
            $table->enum('credit_time', ['instant', 'after_completion'])->default('instant')->change();
        });
    }
};
