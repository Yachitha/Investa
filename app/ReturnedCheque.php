<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnedCheque extends Model
{
    use SoftDeletes;
    protected $fillable = ['acc_id','amount','date','cheque_no'];
}
