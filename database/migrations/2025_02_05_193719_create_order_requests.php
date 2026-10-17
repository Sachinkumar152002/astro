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
        Schema::create('order_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('request_type')->nullable();
            $table->string('reason')->nullable();
            $table->text('message')->nullable();
            $table->integer('status')->comment('0->request, 1->accept, 2->reject')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_requests');
    }
};
