<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablepermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->default(0)->nullable();
            $table->integer('module_id')->default(0)->nullable();
            $table->tinyInteger('can_add')->default(0)->nullable();
            $table->tinyInteger('can_view')->default(0)->nullable();
            $table->tinyInteger('can_update')->default(0)->nullable();
            $table->tinyInteger('can_delete')->default(0)->nullable();
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
        Schema::dropIfExists('permissions');
    }
}
