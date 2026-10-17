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
        Schema::create('astrologer_user_consultacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('astrologer_appointment_id')->default(0);
            $table->foreignId('user_id')->default(0);
            $table->foreignId('astrologer_id')->default(0);
            $table->string('on_going_calls')->nullable();
            $table->string('status')->nullable();
            $table->string('meeting_link', 2048)->nullable();
            $table->string('meeting_date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /*
     *
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_user_consultacies');
    }
};
