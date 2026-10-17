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
        Schema::create('astrologer_lives', function (Blueprint $table) {
            $table->id();
            $table->integer('astrologer_id')->default(0);
            $table->string('astrologer_name')->nullable();
            $table->string('mode')->default('video');
            $table->string('logo')->nullable();
            $table->string('app_id')->nullable();
            $table->string('app_secret')->nullable();
            $table->string('app_sign')->nullable();
            $table->string('room_id')->nullable();
            $table->text('request')->nullable();
            $table->string('status')->default('started');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_lives');
    }
};
