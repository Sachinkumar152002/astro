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
      Schema::table('advertisements', function (Blueprint $table){
        $table->integer('user_id')->default(0);
        $table->string('user_type')->default('admin');
        $table->string('type')->default('free');
        $table->decimal('amount', 10, 2)->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('advertisements', function (Blueprint $table){
        $table->dropColumn('user_id');
        $table->dropColumn('user_type');
        $table->dropColumn('type');
        $table->dropColumn('amount');
      });
    }
};
