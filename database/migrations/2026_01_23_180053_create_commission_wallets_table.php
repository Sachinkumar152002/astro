<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->enum('user_type', ['user', 'astrologer']);
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('lifetime_earnings', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'user_type']);
            $table->index(['user_id', 'user_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_wallets');
    }
};
