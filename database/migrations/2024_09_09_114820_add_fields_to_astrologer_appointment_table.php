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
        Schema::table('astrologer_appointments', function (Blueprint $table) {
            // $table->dropColumn([ 'package_price', 'package_minutes','call_to_date','call_to_time','web_commission','commission_amount']);
            $table->dropColumn('package_price');
            $table->dropColumn('package_minutes');
            $table->dropColumn('call_to_date');
            $table->dropColumn('call_to_time');
            $table->dropColumn('web_commission');
            $table->dropColumn('commission_amount');
            $table->string('_token')->nullable();
            $table->double('actualprice', 10, 2)->default(0);
            $table->integer('astrologer_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('scheduled_for')->default(0)->comment('0: Now, 1: Later');
            $table->integer('timeslot')->default(0);
            $table->string('total_price')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('dateofbirth')->nullable();
            $table->string('birthtime')->nullable();
            $table->string('birthplace')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_appointments', function (Blueprint $table) {
            $table->dropColumn([
                'actualprice', 'astrologer_id', 'user_id', 'scheduled_for', 'timeslot',
                'scheduled_date', 'scheduled_time', 'name', 'email', 'mobile',
                'dateofbirth', 'birthtime', 'birthplace','_token','total_price'
            ]);
        });
    }
};
