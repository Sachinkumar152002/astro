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
      Schema::table('consignments', function (Blueprint $table){
        $table->integer('fragile')->default(0)->comment('1->yes, 0->no');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('consignments', function (Blueprint $table){
        $table->dropColumn('fragile');
      });
    }
};
