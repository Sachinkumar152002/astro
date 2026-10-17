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
        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('identifier')->nullable();
            $table->integer('parent_id')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('media', function (Blueprint $table) {
            $table->integer('folder_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_folders');
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('folder_id');
        });
    }
};
