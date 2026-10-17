<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerImagesToAllModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chakras', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('cms', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('color_therapies', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('festivals', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('mantras', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('meditations', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('palm_readings', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('planets', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('pujas', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('punchangs', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('remedies', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('sub_pages', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('weddings', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('yogas', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });
        Schema::table('zodiac_signs', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
        });

        Schema::table('vastus', function (Blueprint $table) {
            $table->integer('banner_image_id')->default(0);
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
            $table->dropColumn('banner_image_id');
        });
        Schema::table('cms', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('color_therapies', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('festivals', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('mantras', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('meditations', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('palm_readings', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('planets', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('pujas', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('punchangs', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('remedies', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('sub_pages', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('yogas', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
        Schema::table('zodiac_signs', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });

        Schema::table('vastus', function (Blueprint $table) {
            $table->dropColumn('banner_image_id');
        });
    }
}
