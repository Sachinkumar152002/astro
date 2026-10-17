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
        Schema::table('astrologer_remedy_suggestions', function (Blueprint $table) {
            $table->text('pujas')->nullable()->after('reports'); // JSON array of puja IDs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_remedy_suggestions', function (Blueprint $table) {
            $table->dropColumn('pujas');
        });
    }
};
