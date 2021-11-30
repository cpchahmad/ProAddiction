<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use Osiset\ShopifyApp\Traits\ShopModel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements IShopModel
{
    use Notifiable;
    use ShopModel;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /*protected $fillable = [
        'name', 'email', 'password', 'customer_id',
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function agentDetail()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function has_stores(){
        return $this->hasMany('App\AgentStore', 'agent_id','customer_id');

    }
}
