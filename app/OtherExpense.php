<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherExpense extends Model
{
    use SoftDeletes;

    protected $table="other_expenses";
    protected $fillable = ['id','transaction_date','description','amount','user_id','cash_book_id','bank_book_id'];
}
