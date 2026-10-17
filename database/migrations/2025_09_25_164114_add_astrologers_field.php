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
        Schema::table('astrologers', function (Blueprint $table) {
          $table->string("landmark")->nullable();
          $table->tinyInteger("has_website")->default(0);
          $table->string("website_url")->nullable();
          $table->tinyInteger("associate_with_temple")->default(0);
          $table->string("temple_name")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologers', function (Blueprint $table) {
          $table->dropColumn("landmark");
          $table->dropColumn("has_website");
          $table->dropColumn("website_url");
          $table->dropColumn("associate_with_temple");
          $table->dropColumn("temple_name");
        });
    }
};
