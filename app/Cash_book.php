<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string transaction_date
 * @property \Illuminate\Support\Collection description
 * @property float deposit
 * @property mixed withdraw
 * @property \Illuminate\Database\Eloquent\Model|null|object|static balance
 * @property mixed id
 */
class Cash_book extends Model
{
    protected $table="cash_book";
    protected $fillable = [
        'transaction_date', 'description', 'deposit', 'withdraw', 'balance'
    ];

    public function hasRepayment(){
        return $this->hasMany ('App\Customer_repayment','cash_book_id');
    }
}
