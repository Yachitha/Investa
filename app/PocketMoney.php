<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property static transaction_date
 * @property mixed amount
 * @property mixed description
 * @property mixed sales_rep_id
 * @property null cash_book_id
 * @property null bank_book_id
 */
class PocketMoney extends Model
{
    /*
     * Fields=> id, transaction_date, description, amount, sales_rep_id, timestamps, cash_book, bank_book
    **/
    use SoftDeletes;

    protected $table = 'sales_rep_expenses';
    protected /** @noinspection SpellCheckingInspection */
        $fillable = [
        'transaction_date','description','amount','sales_rep_id','cash_book_id','bank_book_id'
    ];

    public function hasBankBook() {
        return $this->belongsTo ('App\Cash_book','cash_book_id');
    }

    public function hasCashBook() {
        return $this->belongsTo ('App\Bank_book','bank_book_id');
    }
}
