<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'route';
    public function hasSalesRep () {
        return $this->hasOne('App\User', 'route_id');
    }

    public function hasCustomers() {
        return $this->hasMany('App\Customer','route_id');
    }
}
