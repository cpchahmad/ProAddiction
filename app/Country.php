<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected function states()
    {
        return $this->hasMany('App\State', 'country_id');
    }
}
