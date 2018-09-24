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
        return $this->belongsTo ('App\Customer_loan','loan_id');
    }

    public function hasBankBook(){
        return $this->belongsTo ('App\Bank_book','bank_book_id');
    }

    public function hasCashBook() {
        return $this->belongsTo ('App\Cash_book', 'cash_book_id');
    }
}
