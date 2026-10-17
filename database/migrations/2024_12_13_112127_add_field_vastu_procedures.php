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
      Schema::table('vastu_procedures', function (Blueprint $table){
        $table->integer('show_on_services')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('vastu_procedures', function (Blueprint $table){
        $table->dropColumn('show_on_services');
      });
    }
};
