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
      Schema::table('call_histories', function (Blueprint $table){
        $table->integer('is_initiated')->default(0);
        $table->string('initiated_time')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('call_histories', function (Blueprint $table){
        $table->dropColumn('is_initiated');
        $table->dropColumn('initiated_time');
      });
    }
};
