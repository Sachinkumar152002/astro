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
      Schema::table('products', function (Blueprint $table){
        $table->integer('hsn_id')->default(0);
        $table->string('hsn_code')->nullable();
        $table->decimal('gst_percentage')->defaul(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('products', function (Blueprint $table){
        $table->dropColumn('hsn_id');
        $table->dropColumn('hsn_code');
        $table->dropColumn('gst_percentage');
      });
    }
};
