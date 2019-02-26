<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank_book extends Model
{
    protected $table = "bank_book";
    protected $fillable = [
        'transaction_date', 'description', 'deposit', 'withdraw', 'balance', 'cheque_no'
    ];

    public function hasRepayments()
    {
        return $this->hasMany('App\Customer_repayment', 'bank_book_id');
    }
}
