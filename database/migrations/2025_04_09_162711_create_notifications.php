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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('astrologer_id')->default(0);
            $table->string('message')->nullable();
            $table->string('link')->nullable();
            $table->string('app_link')->nullable();
            $table->integer('for')->default(0)->comment('0->all, 1->user, 2->astrologer');
            $table->integer('view')->default(0)->comment('0->no, 1->yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
