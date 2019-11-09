<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnPayment extends Model
{
    use SoftDeletes;
    protected $fillable = ['return_cheque_id','date','amount','cheque_id',];

}
