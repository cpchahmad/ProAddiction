<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected function customer()
    {
        return $this->belongsTo('App\Customer', 'shopify_id', 'shopify_id');
    }
    protected function agent()
    {
        return $this->hasOne('App\Customer', 'coupon_code', 'coupon_code');
    }
}
