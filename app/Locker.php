<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locker extends Model
{
    use SoftDeletes;
    protected $table = "lockers";

    protected $fillable = [
        "amount","cash_out"
    ];
}
