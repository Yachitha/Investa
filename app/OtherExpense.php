<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherExpense extends Model
{
    protected $table="other_expenses";
    protected $fillable = ['id','transaction_date','description','amount','user_id','cash_book_id','bank_book_id'];
}
