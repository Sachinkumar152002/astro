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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->string("address")->nullable();
            $table->integer("country_id")->default(0);
            $table->string("country")->nullable();
            $table->integer("state_id")->default(0);
            $table->string("state")->nullable();
            $table->integer("city_id")->default(0);
            $table->string("city")->nullable();
            $table->string("pincode")->nullable();
            $table->string("business_name")->nullable();
            $table->string("gst_number")->nullable();
            $table->string("pan_number")->nullable();
            $table->integer("status")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
