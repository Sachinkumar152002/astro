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
        Schema::table('astrologer_puja_media', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['assignment_id']);

            // Make assignment_id nullable
            $table->unsignedBigInteger('assignment_id')->nullable()->change();

            // Re-add the foreign key constraint with nullable support
            $table->foreign('assignment_id')
                  ->references('id')
                  ->on('puja_astrologer_assignments')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_puja_media', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['assignment_id']);

            // Make assignment_id not nullable again
            $table->unsignedBigInteger('assignment_id')->nullable(false)->change();

            // Re-add the foreign key constraint
            $table->foreign('assignment_id')
                  ->references('id')
                  ->on('puja_astrologer_assignments')
                  ->onDelete('cascade');
        });
    }
};
