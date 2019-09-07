<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use SoftDeletes;

    protected $table = 'route';

    protected $fillable = [
        'name'
    ];

    public function hasSalesRep () {
        return $this->hasOne('App\User', 'route_id');
    }

    public function hasCustomers() {
        return $this->hasMany('App\Customer','route_id');
    }
}
