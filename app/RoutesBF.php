<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed route_id
 * @property mixed date
 * @property mixed amount
 * @property mixed acc
 * @property int|null user_id
 */
class RoutesBF extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'route_id','amount','acc','user_id'
    ];
}
