<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlySheet extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'date','loan_payment','e_income','sup_cash_loan','sup_che_loan',
        'income','loan_cash','loan_che','salary_payment',
        'sup_loan_cash_pay','sup_loan_che_pay','ex_expenses',
        'expenses','monthly_income','monthly_deposit','monthly_profit'
    ];
}
