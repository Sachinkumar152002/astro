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
      Schema::table('ng_softwares', function (Blueprint $table) {
      $table->integer('banner_image_id')->default(0);
        $table->string('page')->nullable();
        $table->text('short_description')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('ng_softwares', function (Blueprint $table) {
        $table->dropColumn('banner_image_id');
        $table->dropColumn('page');
        $table->dropColumn('short_description');
      });
    }
};
