<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arrears extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'loan_id','customer_id','route_id','sales_rep_id','amount','date'
    ];
}
