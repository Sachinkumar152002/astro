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
      Schema::table('temp_astrologers', function (Blueprint $table){
        $table->enum('type', ['astrologer', 'instructor'])->default('astrologer');
        $table->string('review_status')->default('pending');
        $table->integer('is_publish')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('temp_astrologers', function (Blueprint $table){
        $table->dropColumn('type');
        $table->dropColumn('review_status');
        $table->dropColumn('is_publish');
      });
    }
};
