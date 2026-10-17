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
        Schema::create('temp_astrologers', function (Blueprint $table) {
            $table->id();
            $table->integer('astrologer_id')->nullable();
            $table->json('about')->nullable();
            $table->json('firstname')->nullable();
            $table->json('lastname')->nullable();
            $table->json('display_name')->nullable();
            $table->json('phone')->nullable();
            $table->json('email')->nullable();
            $table->json('day')->nullable();
            $table->json('month')->nullable();
            $table->json('year')->nullable();
            $table->json('gender')->nullable();
            $table->json('address')->nullable();
            $table->json('country')->nullable();
            $table->json('state')->nullable();
            $table->json('city')->nullable();
            $table->json('zip_code')->nullable();
            $table->json('call_price')->nullable();
            $table->json('call_special_price')->nullable();
            $table->json('chat_price')->nullable();
            $table->json('chat_special_price')->nullable();
            $table->json('price')->nullable();
            $table->json('special_price')->nullable();
            $table->json('languages')->nullable();
            $table->json('experties')->nullable();
            $table->json('time_avaibility')->nullable();
            $table->json('bank_account_name')->nullable();
            $table->json('bank_account_number')->nullable();
            $table->json('bank_account_number_verify')->nullable();
            $table->json('bank_branch')->nullable();
            $table->json('bank_ifsc_code')->nullable();
            $table->json('bank_name')->nullable();
            $table->json('pan_card')->nullable();
            $table->json('job_start_year')->nullable();
            $table->json('sp_from_date')->nullable();
            $table->json('sp_to_date')->nullable();
            $table->string('status')->nullable();
            $table->string('options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_astrologers');
    }
};
