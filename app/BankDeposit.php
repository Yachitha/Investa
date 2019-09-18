<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankDeposit extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'date','amount','type','no','bank_acc_id'
    ];
}
