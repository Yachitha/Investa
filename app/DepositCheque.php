<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepositCheque extends Model
{
    use SoftDeletes;
    protected $fillable = ['acc_id','amount','cheque_no'];
}
