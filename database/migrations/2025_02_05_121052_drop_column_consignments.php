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
        $table->dropColumn('consignment_request');
        $table->dropColumn('consignment_response');
        $table->dropColumn('pickup_request');
        $table->dropColumn('pickup_response');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('consignments', function (Blueprint $table){
        $table->text('consignment_request');
        $table->text('consignment_response');
        $table->text('pickup_request');
        $table->text('pickup_response');
      });
    }
};
