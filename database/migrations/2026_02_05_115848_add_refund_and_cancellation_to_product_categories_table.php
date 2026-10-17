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
        Schema::table('product_categories', function (Blueprint $table) {
            $table->tinyInteger('refund_and_cancellation')->default(0)->after('is_featured')->comment('0=Disabled, 1=Enabled');
            $table->integer('refund_cancellation_days')->nullable()->after('refund_and_cancellation')->comment('Number of days for refund and cancellation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['refund_and_cancellation', 'refund_cancellation_days']);
        });
    }
};
