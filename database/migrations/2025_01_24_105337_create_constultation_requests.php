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
        Schema::create('consultation_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('user_type')->nullable()->comment('user, astrologer');
            $table->text('message')->nullable();
            $table->string('request_type')->nullable()->comment('cancel, reschedule');
            $table->tinyInteger('status')->default(0)->comment('0->inactive, 1->accept, 2->reject');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_requests');
    }
};
