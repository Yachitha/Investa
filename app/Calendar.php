<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed date
 * @property boolean holiday
 */
class Calendar extends Model
{
    use SoftDeletes;

    protected $table = "calendar";
    protected $fillable = ['date', 'holiday'];
}
