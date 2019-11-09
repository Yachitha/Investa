<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed sales_rep_id
 * @property mixed amount
 * @property mixed date
 * @property string description
 * @property string type
 * @property mixed|null cash_book_id
 * @property mixed|null bank_book_id
 */
class SalesRepSalaryPayment extends Model
{
    use SoftDeletes;
    protected $fillable = ['sales_rep_id','amount','date'];
}
