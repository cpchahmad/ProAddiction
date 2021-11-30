<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentStore extends Model
{
    protected function agent()
    {
        return $this->belongsTo('App\User', 'agent_id', 'customer_id');
    }
}
