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
        Schema::create('l_p_consulations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->unique();
            $table->text('header_script')->nullable();
            $table->text('footer_script')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('display_number')->default(0);
            $table->tinyInteger('display_email')->default(0);
            $table->tinyInteger('display_social')->default(0);
            $table->text('faqs')->nullable();
            $table->text('testimonials')->nullable();
            $table->text('video_testimonials')->nullable();
            $table->text('section_one')->nullable();
            $table->text('section_two')->nullable();
            $table->text('section_three')->nullable();
            $table->text('section_four')->nullable();
            $table->text('section_five')->nullable();
            $table->text('items')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('l_p_consulations');
    }
};
