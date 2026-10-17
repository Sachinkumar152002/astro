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
        Schema::table('notifications', function (Blueprint $table) {
          $table->integer('on')->default(0)->comment('0->notificatin, 1->top');
          $table->tinyInteger('status')->default(0)->comment('0->inactive, 1->active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
          $table->dropColumn('on');
          $table->dropColumn('status');
        });
    }
};
