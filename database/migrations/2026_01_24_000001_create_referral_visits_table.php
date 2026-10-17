<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_visits', function (Blueprint $table) {
            $table->id();
            $table->string('referral_code', 20)->index();
            $table->string('visitor_id', 64)->index(); // cookie-based unique id
            $table->date('visited_on')->index();

            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('landing_url')->nullable();
            $table->text('referrer_url')->nullable();

            $table->timestamps();

            // One visit per visitor per referral code per day
            $table->unique(['referral_code', 'visitor_id', 'visited_on'], 'uniq_ref_visit_day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_visits');
    }
};
