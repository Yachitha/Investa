<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed acc_id
 * @property mixed with_des
 * @property string type
 * @property mixed amount
 * @property mixed cheque_no
 */
class BankWithdraw extends Model
{
    use SoftDeletes;
    protected $fillable = ['acc_id','with_des','amount'];
}
