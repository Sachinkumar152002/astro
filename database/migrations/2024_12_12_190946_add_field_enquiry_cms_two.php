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
      if(!Schema::hasColumn('enquiry_cms_two', 'language')){
        Schema::table('enquiry_cms_two', function (Blueprint $table){
          $table->string('language')->nullable();
        });
      }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      //
    }
};
