<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 */
class Customer_loan extends Model
{
    protected $table = 'customer_loan';
    protected $fillable = [
        'loan_no','loan_amount' ,'start_date', 'end_date', 'customer_id', 'no_of_installments', 'installment_amount', 'interest_rate', 'duration'
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
