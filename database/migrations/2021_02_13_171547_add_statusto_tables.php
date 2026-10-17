<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatustoTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chakras', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('color_therapies', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('festivals', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('mantras', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('meditations', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('palm_readings', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('planets', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('pujas', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('punchangs', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('remedies', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('sub_pages', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('vastus', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('weddings', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('yogas', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });
        Schema::table('zodiac_signs', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chakras', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('color_therapies', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('festivals', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('mantras', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('meditations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('palm_readings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('planets', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('pujas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('punchangs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('remedies', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('sub_pages', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('vastus', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('yogas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('zodiac_signs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
