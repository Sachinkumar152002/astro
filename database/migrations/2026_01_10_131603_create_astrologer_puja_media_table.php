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
        Schema::create('astrologer_puja_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('puja_astrologer_assignments')->onDelete('cascade');
            $table->foreignId('astrologer_id')->constrained('astrologers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('media_id')->nullable(); // For images/photos
            $table->integer('video_id')->nullable(); // For video
            $table->enum('type', ['image', 'video'])->default('image');
            $table->timestamps();

            // Indexes for faster queries
            $table->index(['assignment_id', 'type']);
            $table->index(['astrologer_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_puja_media');
    }
};
