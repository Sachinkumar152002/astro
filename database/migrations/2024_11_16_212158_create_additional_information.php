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
        Schema::create('user_additional_informations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('qualification')->nullable();
            $table->string('institution')->nullable();
            $table->date('passing_year')->nullable();
            $table->string('division_grade')->nullable();
            $table->string('marks')->nullable();
            $table->integer('file')->nullable();
            $table->text('experience')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_additional_informations');
    }
};
