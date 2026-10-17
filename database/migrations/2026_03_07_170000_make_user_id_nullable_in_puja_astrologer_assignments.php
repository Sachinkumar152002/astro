<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make user_id nullable so assignment can be created when order's user no longer exists in users table.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('puja_astrologer_assignments', 'user_id')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        if ($driver !== 'mysql') {
            Schema::table('puja_astrologer_assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            });
            return;
        }

        // MySQL: drop FK by name, modify column, re-add FK
        try {
            DB::statement('ALTER TABLE puja_astrologer_assignments DROP FOREIGN KEY puja_astrologer_assignments_user_id_foreign');
        } catch (\Exception $e) {
            // Constraint name may differ, try dropping by column
            try {
                Schema::table('puja_astrologer_assignments', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Exception $e2) {
                // Ignore
            }
        }

        // Make user_id nullable (column may be INT or BIGINT)
        try {
            DB::statement('ALTER TABLE puja_astrologer_assignments MODIFY COLUMN user_id INT UNSIGNED NULL');
        } catch (\Exception $e) {
            DB::statement('ALTER TABLE puja_astrologer_assignments MODIFY COLUMN user_id INT UNSIGNED NULL');
        }

        Schema::table('puja_astrologer_assignments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('puja_astrologer_assignments', 'user_id')) {
            return;
        }
        Schema::table('puja_astrologer_assignments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        DB::statement('ALTER TABLE puja_astrologer_assignments MODIFY COLUMN user_id BIGINT UNSIGNED NOT NULL');
        Schema::table('puja_astrologer_assignments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
