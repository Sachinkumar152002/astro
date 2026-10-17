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
      if (!Schema::hasColumn('course_module_questions', 'module_id')) {
        Schema::table('course_module_questions', function (Blueprint $table) {
            $table->integer('module_id')->default(0);
        });
      }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_module_questions', function (Blueprint $table) {
            $table->dropColumn('module_id');
        });
    }
};
