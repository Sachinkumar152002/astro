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
        Schema::create('verify_users', function (Blueprint $table) {
            $table->id();
            $table->string("phone")->nullable();
            $table->string("phone_otp")->nullable();
            $table->integer("phone_verified")->default(0);
            $table->string("email")->nullable();
            $table->string("email_otp")->nullable();
            $table->integer("email_verified")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verify_users');
    }
};
