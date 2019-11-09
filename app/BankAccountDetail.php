<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccountDetail extends Model
{
    protected $table = 'bank_account_details';
    protected $fillable = ['account_no','bank_name','balance','total_cash_in','total_cash_out'];
}
