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
          $table->integer('apply_gst')->default(0)->comment('0->No, 1->Yes');
          $table->integer('price_type')->default(1)->comment('0->exclude gst, 1->include gst');
          $table->decimal('gst_percentage')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('apply_gst');
            $table->dropColumn('price_type');
            $table->dropColumn('gst_percentage');
        });
    }
};
