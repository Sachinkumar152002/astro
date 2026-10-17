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
      Schema::table('preview_videos', function (Blueprint $table) {
        $table->string('video_hash')->nullable();
        $table->string('type')->default('youtube');
        $table->string('item_type')->default('course');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('preview_videos', function (Blueprint $table) {
        $table->dropColumn('video_hash');
        $table->dropColumn('type');
        $table->dropColumn('item_type');
      });
    }
};
