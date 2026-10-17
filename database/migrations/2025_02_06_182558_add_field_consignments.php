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
        $table->integer('type')->default(0)->comment('1->order, 2->return');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('consignments', function (Blueprint $table){
        $table->dropColumn('type');
      });
    }
};
