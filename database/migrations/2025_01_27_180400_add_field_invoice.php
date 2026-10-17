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
      Schema::table('invoices', function (Blueprint $table){
        $table->string('gst')->nullable()->default('08ABLPB8707H3ZJ');
        $table->string('pan')->nullable()->default('ABLPB8707H');
        $table->string('bill_from_company_name')->nullable()->default('Astrokavach');
        $table->string('bill_from_company_address')->nullable()->default('Surat, Gujarat');
        $table->string('ship_from_company_name')->nullable()->default('Astrokavach');
        $table->string('ship_from_company_address')->nullable()->default('Surat, Gujarat');
        $table->string('nature_of_supply')->nullable()->default('goods');
        $table->integer('status')->default(1)->comment('0->cancel, 1->success');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('invoices', function (Blueprint $table){
        $table->dropColumn('gst');
        $table->dropColumn('pan');
        $table->dropColumn('bill_from_company_name');
        $table->dropColumn('bill_from_company_address');
        $table->dropColumn('ship_from_company_name');
        $table->dropColumn('ship_from_company_address');
        $table->dropColumn('nature_of_supply');
        $table->dropColumn('status');
      });
    }
};
