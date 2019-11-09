<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierLoanPayment extends Model
{
    protected $table = "supplier_loan_repayment";

    use SoftDeletes;

    protected $fillable = ['loan_id','date','amount','type'];
}
