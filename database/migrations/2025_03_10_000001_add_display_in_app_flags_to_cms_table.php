<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisplayInAppFlagsToCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms', function (Blueprint $table) {
            if (!Schema::hasColumn('cms', 'display_in_user_app')) {
                $table->tinyInteger('display_in_user_app')->default(0);
            }
            if (!Schema::hasColumn('cms', 'display_in_customer_app')) {
                $table->tinyInteger('display_in_customer_app')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms', function (Blueprint $table) {
            $table->dropColumn(['display_in_user_app', 'display_in_customer_app']);
        });
    }
}
