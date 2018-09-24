<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Customer_loan;
use App\Customer_repayment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
                if(Customer::where('customer_no',$request->customer_no)->first()){
                    $loan = Customer_loan::create([
                        'loan_no'=>$request->loan_no,
                        'interest_rate' => $request->interest_rate,
                        'loan_amount' => $request->loan_amount,
                        'installment_amount'=>$request->installment_amount,
                        'no_of_installments'=>$request->no_of_installments,
                        'start_date'=>$request->start_date,
                        'end_date'=>$request->end_date,
                        'customer_id'=>Customer::where('customer_no',$request->customer_no)->first()->id,
                        'duration'=>$request->no_of_installments,
                    ]);

                    if ($loan) {
                        $repayment = new Customer_repayment();
                        $repayment->loan_id = $loan->id;
                        $repayment->bank_book_id = null;
                        $repayment->cash_book_id = null;
                        $repayment->amount = $request->loan_amount;
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
