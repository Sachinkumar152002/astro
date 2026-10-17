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
      Schema::create('specifications', function (Blueprint $table){
        $table->integer('item_id')->default(0);
        $table->integer('icon_id')->default(0);
        $table->string('heading')->nullable();
        $table->string('text')->nullable();
        $table->string('item_type')->nullable();
        $table->string('type')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specifications');
    }
};
