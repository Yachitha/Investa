<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaySheet extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'sales_rep_id','month','total','start','end','commission'
    ];
}
