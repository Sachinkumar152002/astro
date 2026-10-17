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
        Schema::table('puja_mahurats', function (Blueprint $table) {
            $table->boolean('reminder_4hr_sent')->default(false)->after('time');
            $table->boolean('reminder_2hr_sent')->default(false)->after('reminder_4hr_sent');
            $table->boolean('reminder_1hr_sent')->default(false)->after('reminder_2hr_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('puja_mahurats', function (Blueprint $table) {
            $table->dropColumn(['reminder_4hr_sent', 'reminder_2hr_sent', 'reminder_1hr_sent']);
        });
    }
};
