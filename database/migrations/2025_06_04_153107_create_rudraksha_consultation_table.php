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
        Schema::create('rudraksha_consultation', function (Blueprint $table) {
            $table->id();
            $table->string('fullName', 256)->nullable();
            $table->string('email', 256)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->date('dob')->nullable();
            $table->time('birthTime')->nullable();
            $table->string('birthPlace', 512)->nullable();
            $table->text('message')->nullable();
            $table->string('amount', 50)->nullable();
            $table->integer('payment_status')->nullable();
            $table->string('payment_id', 128)->nullable();
            $table->string('payment_method', 128)->nullable();
            $table->string('module', 256)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rudraksha_consultation');
    }
};
