<?php

namespace App\Http\Controllers\api;

use App\Customer_repayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Customer_loan;
use App\Customer;

class LoginController extends Controller
{
    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;
        $remember = $request->remember;
        if(Auth::attempt (['email'=>$email,'password'=>$password],$remember)){
            $user = Auth::user ();
            $customers = collect ($user->hasCustomer);
            $array1 = [];
            $array2 = [];
            foreach ($customers as $customer){
                $loans = Customer::find($customer->id)->hasLoan;
                $array1 []=$loans;
                foreach ($loans as $loan){
                    $repayment = Customer_loan::find($loan->id)->hasRepayment;
                    $array2[]=$repayment;
                }
            }
            return response ()->json ([
                'error'=>FALSE,
                'user'=>$user,
                'customer_loans'=>collect ($array1),
                'customer_repayments'=>collect ($array2),
            ],200);
        }
        else{
            return response ()->json ([
                'error'=>TRUE,
                'message'=>"Login Credentials are wrong!"
            ],401);
        }
    }
}
