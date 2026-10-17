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
        Schema::table('astrologers', function (Blueprint $table) {
            $table->decimal('chat_price', 8, 2)->default(0.0);
            $table->decimal('chat_special_price', 8, 2)->default(0.0);
            $table->decimal('call_price', 8, 2)->default(0.0);
            $table->decimal('call_special_price', 8, 2)->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologers', function (Blueprint $table) {
            //
        });
    }
};
