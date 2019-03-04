<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 */
class SalesRepCommission extends Model
{
    protected $table = "sales_rep_commission";

    protected $fillable = [
        "commission_rate", "commission_amount", "date", "loan_id", "user_id"
    ];
}
