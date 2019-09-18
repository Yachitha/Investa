<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoutesBF extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'route_id','amount','acc','user_id'
    ];
}
