<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstrologerReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('astrologer_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('astrologer_id')->default(0);
            $table->integer('user_id')->nullable()->default(0);
            $table->integer('call_id')->nullable()->default(0);
            $table->integer('chat_id')->nullable()->default(0);
            $table->integer('review')->nullable()->default(0);
            $table->longText('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('astrologer_reviews');
    }
}
