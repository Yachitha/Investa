<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string transaction_date
 * @property string description
 * @property float deposit
 * @property mixed withdraw
 * @property mixed cheque_no
 * @property int balance
 * @property mixed id
 */
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
