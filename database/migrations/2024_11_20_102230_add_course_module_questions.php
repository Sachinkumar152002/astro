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
      Schema::table('course_module_questions', function (Blueprint $table) {
        $table->string('show_in')->default('assignment');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('course_module_questions', function (Blueprint $table) {
        $table->dropColumn('show_in');
      });
    }
};
