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
      if (!Schema::hasColumn('astrologer_appointments', 'module_id')) {
        Schema::table('astrologer_appointments', function (Blueprint $table) {
            $table->string('occupation')->nullable();
            $table->string('maritial_status')->nullable(0);
        });
      }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_appointments', function (Blueprint $table) {
            $table->dropColumn('occupation');
            $table->dropColumn('maritial_status');
        });
    }
};
