<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_id')->nullable();

            $table->bigInteger('shop_id')->nullable();
            $table->string('first_name')->nullable();

            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->date('date_started')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('seller_area')->nullable();
            $table->string('seller_code')->nullable();
            $table->string('seller_color')->nullable();
            $table->integer('commission')->nullable();
            $table->integer('is_agent')->nullable();
            $table->string('coupon_code')->nullable();
            $table->float('discount')->nullable();
            $table->bigInteger('price_rule_id')->nullable();


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
        Schema::dropIfExists('customers');
    }
}
