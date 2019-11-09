<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property  mixed customer_id
 * @property integer customer_no
 * @property string status
 * @property int disable_count
 */
class DisableCustomer extends Model
{
    use SoftDeletes;

    protected $table = 'disable_customers';

    protected $fillable = ['customer_id','customer_no','status','disable_count'];
}
