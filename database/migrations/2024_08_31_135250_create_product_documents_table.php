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
        Schema::create('product_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->default(0);
            $table->string('title')->nullable();
            $table->string('file_name')->nullable();
            $table->string('caption')->nullable();
            $table->string('type')->nullable();
            $table->json('attributes')->nullable();
            $table->integer('size')->unsigned();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_documents');
    }
};
