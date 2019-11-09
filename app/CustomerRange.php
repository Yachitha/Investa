<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerRange extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'start','end','route_id'
    ];
}
