<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected  $guarded = [];
    protected function orders()
    {
        return $this->hasMany('App\Order', 'shopify_id');
    }
    protected function has_order()
    {
        return $this->hasMany('App\Order', 'coupon_code', 'coupon_code');
    }
}
