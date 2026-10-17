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
        Schema::create('kundali_matchings', function (Blueprint $table) {
            $table->id();
            $table->integer("kundali_matching_enquiries_id")->default(0);
            $table->string("lang")->nullable();
            $table->longText("response")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kundali_matchings');
    }
};
