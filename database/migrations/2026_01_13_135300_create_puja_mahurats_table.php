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
        Schema::create('puja_mahurats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->unique()->constrained('puja_astrologer_assignments')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();

            // Index for faster queries
            $table->index('assignment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puja_mahurats');
    }
};
