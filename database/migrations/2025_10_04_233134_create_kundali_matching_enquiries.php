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
        Schema::create('kundali_matching_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string("boy_name")->nullable();
            $table->date("boy_birth_date")->nullable();
            $table->time("boy_birth_time")->nullable();
            $table->string("boy_birth_place")->nullable();
            $table->string("boy_lat")->nullable();
            $table->string("boy_lon")->nullable();
            $table->string("girl_name")->nullable();
            $table->date("girl_birth_date")->nullable();
            $table->time("girl_birth_time")->nullable();
            $table->string("girl_birth_place")->nullable();
            $table->string("girl_lat")->nullable();
            $table->string("girl_lon")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kundali_matching_enquiries');
    }
};
