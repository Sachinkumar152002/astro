<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->decimal('commission_value', 10, 2)->default(0);
            $table->decimal('max_cap', 10, 2)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique('category_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_commissions');
    }
};
