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
        $table->date('meet_date')->nullable();
        $table->time('meet_start_time')->nullable();
        $table->time('meet_end_time')->nullable();
        $table->string('meet_link')->nullable();
        $table->string('meet_type')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('courses', function (Blueprint $table) {
        $table->dropColumn('meet_date');
        $table->dropColumn('meet_start_time');
        $table->dropColumn('meet_end_time');
        $table->dropColumn('meet_link');
        $table->dropColumn('meet_type');
      });
    }
};
