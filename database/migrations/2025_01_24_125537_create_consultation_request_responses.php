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
        Schema::create('consultation_request_responses', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id')->default(0);
            $table->integer('request_id')->default(0);
            $table->integer('admin_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('user_type')->nullable()->comment('user, astrologer');
            $table->text('user_message')->nullable();
            $table->text('astrologer_message')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1->inactive, 1->accept, 2->reject');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_request_responses');
    }
};
