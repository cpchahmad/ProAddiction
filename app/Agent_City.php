<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent_City extends Model
{
    protected $fillable = [
        'agent_code',
        'city',
    ];
}
