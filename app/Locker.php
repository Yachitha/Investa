<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed date
 * @property mixed amount
 * @property mixed cash_out
 * @property null extra_str1
 * @property int extra_int1
 * @property float extra_float1
 */
class Locker extends Model
{
    use SoftDeletes;
    protected $table = "lockers";

    protected $fillable = [
        "amount","cash_out"
    ];
}
