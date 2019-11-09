<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankWithdrawDesciption extends Model
{
    use SoftDeletes;
    protected $fillable = ['description'];
}
