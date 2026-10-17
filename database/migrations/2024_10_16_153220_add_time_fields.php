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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('start_date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->integer('purchase_type')->default(0);
            $table->integer('total_month')->default(0);
            $table->integer('total_class')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->dropColumn('purchase_type');
            $table->dropColumn('total_month');
            $table->dropColumn('total_class');
        });
    }
};
