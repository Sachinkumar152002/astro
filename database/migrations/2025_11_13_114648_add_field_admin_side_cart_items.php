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
        Schema::table('admin_side_cart_items', function (Blueprint $table) {
          $table->integer("user_temp_cart_id")->default(0)->after("id");
          $table->string("image_url")->nullable()->after("item_type");
          $table->string("title")->nullable()->after("item_type");
          $table->decimal("tax_percentage",10,2)->default(0)->after("unit_sale_price");
          $table->string("hsn_code")->nullable()->after("unit_sale_price");
          $table->integer("hsn_code_id")->default(0)->after("unit_sale_price");
        });
        Schema::table('order_items', function (Blueprint $table) {
          $table->decimal("net_total",10,2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_side_cart_items', function (Blueprint $table) {
          $table->dropColumn("user_temp_cart_id");
          $table->dropColumn("image_url");
          $table->dropColumn("title");
          $table->dropColumn("tax_percentage");
          $table->dropColumn("hsn_code");
          $table->dropColumn("hsn_code_id");
        });
        Schema::table('order_items', function (Blueprint $table) {
          $table->dropColumn("order_items");
        });
    }
};
