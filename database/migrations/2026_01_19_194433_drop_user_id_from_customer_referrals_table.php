<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key constraint first using raw SQL
        DB::statement('ALTER TABLE customer_referrals DROP FOREIGN KEY customer_referrals_user_id_foreign');

        Schema::table('customer_referrals', function (Blueprint $table) {
            // Drop the user_id column
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_referrals', function (Blueprint $table) {
            // Add back the user_id column if rollback is needed
            $table->unsignedInteger('user_id')->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
