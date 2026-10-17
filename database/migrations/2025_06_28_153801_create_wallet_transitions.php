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
        Schema::create('wallet_transitions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('user_type')->nullable();
            $table->integer('table_id')->default(0);
            $table->string('table_name')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->integer('action')->default(0)->comment('0->debit, 1->credit');
            $table->integer('status')->default(0)->comment('0->failed, 1->success');
            $table->string('remark')->nullable();
            $table->tinyInteger('invoice')->default(0)->comment('0->no, 1->yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transitions');
    }
};
