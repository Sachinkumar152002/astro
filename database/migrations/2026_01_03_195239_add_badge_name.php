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
        Schema::table('badges', function (Blueprint $table) {
            if (!Schema::hasColumn('badges', 'name')) {
                $table->string('name',20)->nullable();
            }
            if (!Schema::hasColumn('badges', 'status')) {
                $table->tinyInteger('status')->default(1);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('badges', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('status');
        });
    }
};
