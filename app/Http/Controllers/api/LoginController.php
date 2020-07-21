<?php /** @noinspection ALL */

namespace App\Http\Controllers\api;

use App\Route;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Customer_loan;
use App\Customer;
use Illuminate\Support\Facades\DB;

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

    public function liteLogin(Request $request) {
        $email = $request['email'];
        $password = $request['password'];
        $remember = $request['remember'];

        if (Auth::attempt(['email'=>$email,'password'=>$password],$remember)){
            $user = Auth::user();
            $route = User::find(Auth::id())->hasRoute;
            $customers = Route::find($route->id)->hasCustomers;

            $loan_details = [];

            foreach ($customers as $customer) {
                $active_loan = DB::table('customer_loan')
                    ->where('customer_id','=',$customer->id)
                    ->where('isFinished','=',false)
                    ->whereNull('deleted_at')
                    ->first();
                if ($active_loan) {
                    $loan_repayment = DB::table('customer_loan_repayment')
                        ->where('loan_id','=',$active_loan->id)
                        ->whereNull('deleted_at')
                        ->orderBy('id','desc')
                        ->first();

                    $total_paid_amount = $active_loan->total_loan_amount - $loan_repayment->remaining_amount;

                    $loan_detail = [
                        'customer_id'=>$customer->id,
                        'total_paid_amount'=>$total_paid_amount,
                        'total_loan_amount'=>$active_loan->total_loan_amount,
                        'installment_count'=>$loan_repayment->installment_count,
                        'total_installments'=>$active_loan->no_of_installments,
                        'remaining_amount'=>$loan_repayment->remaining_amount,
                        'loan_amount'=>$active_loan->loan_amount,
                        'arrears_amount'=>$active_loan->arrears_amount,
                        'loan_id'=>$active_loan->id,
                        'isFinished'=>$active_loan->isFinished,
                        'repayment_id'=>$loan_repayment->id
                    ];
                    $loan_details[] = $loan_detail;
                }

            }
            return response ()->json ([
                'error'=>FALSE,
                'user'=>$user,
                'customers'=>$customers,
                'loan_details'=>$loan_details
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
