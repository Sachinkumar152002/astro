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
        Schema::create('pandits', function (Blueprint $table){
          $table->id();
          $table->integer("image_id")->default(0);
          $table->string("name")->nullable();
          $table->string("slug")->nullable();
          $table->string("email")->nullable();
          $table->string("phone")->nullable();
          $table->text("about")->nullable();
          $table->tinyInteger("status")->default(0);
          $table->string("meta_title")->nullable();
          $table->string("meta_keywords")->nullable();
          $table->string("meta_description")->nullable();
          $table->integer("order")->default(0);
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pandits');
    }
};
