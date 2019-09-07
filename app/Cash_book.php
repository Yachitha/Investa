<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property string transaction_date
 * @property Collection description
 * @property float deposit
 * @property mixed withdraw
 * @property Model|null|object|static balance
 * @property mixed id
 */
class Cash_book extends Model
{
    use SoftDeletes;

    protected $table="cash_book";
    protected $fillable = [
        'transaction_date', 'description', 'deposit', 'withdraw', 'balance'
    ];

    public function hasRepayment(){
        return $this->hasMany ('App\Customer_repayment','cash_book_id');
    }
}
