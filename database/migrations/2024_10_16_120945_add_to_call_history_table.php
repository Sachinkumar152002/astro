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
        Schema::table('call_histories', function (Blueprint $table) {
            $table->string('callSid')->nullable();
            $table->string('accountSid')->nullable();
            $table->string('callUri')->nullable();
            $table->string('callStatus')->nullable();
            $table->string('recordingUrl')->nullable();
            $table->string('callerName')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('call_histories', function (Blueprint $table) {
            //
        });
    }
};
