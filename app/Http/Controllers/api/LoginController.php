<?php

namespace App\Http\Controllers\api;

use App\Route;
use App\User;
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
            $route = User::find(Auth::id())->hasRoute;
            $customers = Route::find($route->id)->hasCustomers;
            $loans=[];
            $repayment=[];
            foreach ($customers as $customer){
                $loans = collect ($loans)->merge(Customer::find($customer->id)->hasLoan);
                foreach ($loans as $loan){
                    $repayment = collect ($repayment)->merge (Customer_loan::find($loan->id)->hasRepayment);
                }
            }
            return response ()->json ([
                'error'=>FALSE,
                'user'=>$user,
                'customers'=>$customers,
                'loans'=>$loans,
                'repayments'=>$repayment
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
