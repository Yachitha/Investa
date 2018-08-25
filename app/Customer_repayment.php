<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer_repayment extends Model
{
    protected $table = 'customer_loan_repayment';
    protected $fillable = [
        'amount', 'installment_count', 'remaining_amount', 'loan_id',
    ];

    public function hasCustomerLoan(){
        return $this->belongsTo ('Customer_loan','loan_id');
    }
}
