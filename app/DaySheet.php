<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string date
 * @property mixed bf_amount
 * @property mixed route_id
 * @property mixed loan_payment
 * @property mixed e_income
 * @property mixed sup_cash_loan
 * @property mixed sup_che_loan
 * @property mixed emp_loan_payment
 * @property mixed access
 * @property mixed income
 * @property mixed loan_cash
 * @property mixed loan_che
 * @property mixed salary_payment
 * @property mixed sup_loan_cash_pay
 * @property mixed sup_loan_che_pay
 * @property mixed emp_loan
 * @property mixed ex_expenses
 * @property mixed total_income
 * @property mixed bank_deposit
 * @property mixed day_cash
 * @property mixed cash_out
 * @property mixed balance
 * @property mixed cash_in_locker
 * @property mixed day_profit
 * @property mixed expenses
 */
class DaySheet extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'date','bf_amount','route_id','loan_payment','e_income','sup_cash_loan','sup_che_loan',
        'emp_loan_payment','access','income','loan_cash','loan_che','salary_payment',
        'sup_loan_cash_pay','sup_loan_che_pay','emp_loan','ex_expenses','expenses','total_income',
        'bank_deposit','day_cash','cash_out','balance','cash_in_locker','day_profit'
    ];
}
