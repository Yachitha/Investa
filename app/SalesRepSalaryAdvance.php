<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesRepSalaryAdvance extends Model
{
    protected $fillable = ['sales_rep_id','amount','date'];
}
