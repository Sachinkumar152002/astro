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
        Schema::table('astrologers', function (Blueprint $table) {
            $table->string('qualification')->nullable()->after('job_start_year');
            $table->tinyInteger('working_with_other_app')->default(0)->after('qualification')->comment('0=No, 1=Yes');
            $table->string('area')->nullable()->after('address');
            $table->string('instagram')->nullable()->after('landmark');
            $table->string('facebook')->nullable()->after('instagram');
            $table->string('youtube')->nullable()->after('facebook');
            $table->tinyInteger('any_healing')->default(0)->after('youtube')->comment('0=No, 1=Yes');
            $table->string('aadhaar_number', 20)->nullable()->after('pan_card');
            $table->unsignedBigInteger('aadhaar_front_id')->nullable()->after('address_proof')->comment('Media id for Aadhaar front');
            $table->unsignedBigInteger('aadhaar_back_id')->nullable()->after('aadhaar_front_id')->comment('Media id for Aadhaar back');
            $table->unsignedBigInteger('cancel_cheque_id')->nullable()->after('passbook_id');
            $table->unsignedBigInteger('astrology_certificate_id')->nullable()->after('cancel_cheque_id');
            $table->string('bank_city')->nullable()->after('bank_branch');
            $table->string('bank_state')->nullable()->after('bank_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologers', function (Blueprint $table) {
            $table->dropColumn([
                'qualification',
                'working_with_other_app',
                'area',
                'instagram',
                'facebook',
                'youtube',
                'any_healing',
                'aadhaar_number',
                'aadhaar_front_id',
                'aadhaar_back_id',
                'cancel_cheque_id',
                'astrology_certificate_id',
                'bank_city',
                'bank_state',
            ]);
        });
    }
};
