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
        Schema::create('location_histories', function (Blueprint $table) {
            $table->id();
            $table->string('latLonId')->nullable();
            $table->string('place')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('latDeg')->nullable();
            $table->string('latMin')->nullable();
            $table->string('latSec')->nullable();
            $table->string('ns')->nullable();
            $table->string('longDeg')->nullable();
            $table->string('longMin')->nullable();
            $table->string('longSec')->nullable();
            $table->string('ew')->nullable();
            $table->string('timeZoneHour')->nullable();
            $table->string('timeZoneMin')->nullable();
            $table->string('dstV')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_histories');
    }
};
