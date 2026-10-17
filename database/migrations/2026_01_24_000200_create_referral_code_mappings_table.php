<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_code_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 20)->index(); // user | astrologer
            $table->unsignedBigInteger('entity_id')->index();

            $table->string('old_code', 20)->nullable()->unique();
            $table->string('new_code', 20)->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_code_mappings');
    }
};
