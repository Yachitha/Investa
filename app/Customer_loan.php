<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed id
 */
class Customer_loan extends Model
{
    use SoftDeletes;

    protected $table = 'customer_loan';
    protected $fillable = [
        'loan_no','loan_amount' ,'start_date', 'end_date', 'customer_id', 'no_of_installments',
        'installment_amount', 'interest_rate', 'duration', 'total_loan_amount', 'isFinished',
        'isArrears','due_amount','arrears_amount','type','cheque_no','arrears_next'
    ];

    public function hasCustomer(){
        return $this->belongsTo ('App\Customer','customer_id');
    }

    public function hasRepayment(){
        return $this->hasMany ('App\Customer_repayment','loan_id');
    }

    public function hasCommission() {
        return $this->hasOne('App\SalesRepCommission','loan_id');
    }
}
