<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $fillable = [
        'customer_no', 'name', 'email', 'NIC', 'contact_no', 'status', 'addLine1', 'addLine2', 'city', 'route_id'
    ];

    public function hasSalesRep(){
        return $this->belongsTo ('App\User','salesRep_id');
    }

    public function hasLoan(){
        return $this->hasMany ('App\Customer_loan','customer_id');
    }
}
