<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_line_Item extends Model
{
    protected $fillable = [
        'order_id',
        'shopify_order_id',
        'shopify_product_id',
        'coupon_code',
        'sku',
        'title',
        'quantity',
        'price',
        'item_src',
    ];
}
