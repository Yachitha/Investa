<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierLoan extends Model
{
    protected $table = "supplier_loan";
    use SoftDeletes;

    protected $fillable = ['user_id','amount','type'];
}
