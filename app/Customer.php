<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, $customer_no)
 */
class Customer extends Model
{
    protected $table = 'customer';
    protected $fillable = [
        'customer_no', 'name', 'email', 'NIC', 'contact_no', 'status', 'addLine1', 'addLine2', 'city', 'route_id'
    ];

    public function hasRoute(){
        return $this->belongsTo ('App\Route','route_id');
    }

    public function hasLoan(){
        return $this->hasMany ('App\Customer_loan','customer_id');
    }
}
