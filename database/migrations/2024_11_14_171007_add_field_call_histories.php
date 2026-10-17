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
      Schema::table('call_histories', function (Blueprint $table) {
        $table->text('response')->nullable();
        $table->integer('consultancy_id')->nullable();
        $table->double('per_minute_charge',10, 2)->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('call_histories', function (Blueprint $table) {
        $table->dropColumn('response');
        $table->dropColumn('consultancy_id');
        $table->dropColumn('per_minute_charge');
      });
    }
};
