<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_line_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->text('shopify_order_id')->nullable();
            $table->text('shopify_product_id')->nullable();
            $table->text('coupon_code')->nullable();
            $table->text('sku')->nullable();
            $table->text('title')->nullable();
            $table->text('price')->nullable();
            $table->text('quantity')->nullable();
            $table->text('variant_id')->nullable();
            $table->text('item_src');
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
        Schema::dropIfExists('order_line_items');
    }
}
