<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
