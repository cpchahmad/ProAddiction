<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('shop_id')->nullable();
            $table->string('agent_name')->nullable();
            $table->bigInteger('agent_code')->nullable();
            $table->string('total_order')->nullable();
            $table->bigInteger('commission')->nullable();
            $table->string('sell_area')->nullable();
            $table->string('customer_name')->nullable();
            $table->longText('shiping_address')->nullable();
            $table->bigInteger('shopify_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('order_name')->nullable();
            $table->float('total_price')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('refund')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
