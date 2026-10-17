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
            $table->text('experience')->nullable();
            $table->text('education')->nullable();
            $table->text('expertise')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologers', function (Blueprint $table) {
          $table->dropColumn('experience');
          $table->dropColumn('education');
          $table->dropColumn('expertise');
        });
    }
};
