<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed id
 */
class SalesRepCommission extends Model
{
    use SoftDeletes;

    protected $table = "sales_rep_commission";

    protected $fillable = [
        "commission_rate", "commission_amount", "date", "loan_id", "user_id"
    ];
}
