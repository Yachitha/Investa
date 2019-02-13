<?php

namespace App\Http\Controllers;

use App\Cash_book;
use App\Customer;
use App\Customer_loan;
use App\Customer_repayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function createLoan(Request $request){
        $validate = Validator::make($request->all (),[
            'customer_no'=>'required',
            'loan_no'=>'required',
            'loan_amount'=>'required',
            'interest_rate'=>'required',
            'no_of_installments'=>'required',
            'installment_amount'=>'required',
            'start_date'=>'required',
            'end_date'=>'required'
        ]);

        if($validate->fails ()){
            return redirect ('/createLoan')->withErrors ($validate)->withInput ();
        }else{
            try{
                $customer = Customer::where('customer_no',$request->customer_no)->first();
                if($customer){
                    $loan = Customer_loan::create([
                        'loan_no'=>$request->loan_no,
                        'interest_rate' => $request->interest_rate,
                        'loan_amount' => $request->loan_amount,
                        'installment_amount'=>$request->installment_amount,
                        'no_of_installments'=>$request->no_of_installments,
                        'start_date'=>$request->start_date,
                        'end_date'=>$request->end_date,
                        'customer_id'=>$customer->id,
                        'duration'=>$request->no_of_installments,
                    ]);

                    if ($loan) {

                        $cash_withdraw = new Cash_book();
                        $cash_withdraw->transaction_date = Carbon::now ()->format ("Y-m-d H:i:s");
                        $cash_withdraw->description = "given new loan to ".$customer->name;
                        $cash_withdraw->deposit = 0.0;
                        $cash_withdraw->withdraw = $loan->loan_amount;
                        $last_balance_row = DB::table ( 'cash_book' )->orderBy ( 'id' , 'desc' )->first ();
                        if ($last_balance_row){
                            $cash_withdraw->balance = $last_balance_row->balance - $loan->loan_amount;
                        }else {
                            $cash_withdraw->balance = -$loan->amount;
                        }

                        $cash_withdraw->save ();


                        $repayment = new Customer_repayment();
                        $repayment->loan_id = $loan->id;
                        $repayment->bank_book_id = null;
                        $repayment->cash_book_id = null;
                        $repayment->amount = 0.0;
                        $repayment->installment_count = $request->no_of_installments;
                        $repayment->remaining_amount = $request->loan_amount;
                        $repayment->save ();

                        $customer = Customer::where ( 'customer_no' , $request->customer_no )->first ();

                        if ( $customer ) {
                            $customer->status = "ongoing";
                            $customer->save ();
                        }
                        return redirect ( '/createLoan' )->with ( 'message' , 'Loan created successfully' );
                    }
                }else{
                    return redirect ('/createLoan')->with ('error','There is no customer to given customer number');
                }
            }catch (ModelNotFoundException $e){
                return redirect ('/createLoan')->with ('error','Error occurred while creating loan');
            }
        }
    }
}
