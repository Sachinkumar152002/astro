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
        Schema::table('api_urls', function (Blueprint $table) {
          $table->string("key")->nullable();
          $table->string("category")->nullable();
          $table->string("report_code")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_urls', function (Blueprint $table) {
          $table->dropColumn("key");
          $table->dropColumn("category");
          $table->dropColumn("report_code");
        });
    }
};
