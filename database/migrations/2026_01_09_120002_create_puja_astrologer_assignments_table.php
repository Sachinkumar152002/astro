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
        if (!Schema::hasTable('puja_astrologer_assignments')) {
            Schema::create('puja_astrologer_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('astrologer_id')->constrained('astrologers')->onDelete('cascade');
                $table->string('status')->default('assigned'); // assigned, completed, cancelled
                $table->timestamp('assigned_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                // Index for faster queries
                $table->index(['order_id', 'order_item_id']);
                $table->index(['astrologer_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puja_astrologer_assignments');
    }
};
