<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property  integer route_id
 * @property  integer start
 * @property  integer end
 */
class CustomerRange extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'start','end','route_id'
    ];
}
