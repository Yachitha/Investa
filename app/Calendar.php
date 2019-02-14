<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed date
 * @property boolean holiday
 */
class Calendar extends Model
{
    protected $table = "calendar";
    protected $fillable = ['date', 'holiday'];
}
