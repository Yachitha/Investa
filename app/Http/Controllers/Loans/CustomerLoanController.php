<?php
/**
 * Created by PhpStorm.
 * User: yachitha
 * Date: 2/16/19
 * Time: 10:21 PM
 */

namespace App\Http\Controllers\Loans;


use App\Bank_book;
use App\Cash_book;
use App\Customer;
use App\Customer_repayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerLoanController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLoanDetailsForCustomer (Request $request) {
        $validator = Validator::make($request->all(),[
            'parameter' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>$validator->errors()
            ]);
        } else {
            try {
                $customer_id = DB::table('customer')->where('customer_no','=',$request['parameter'])
                    ->orWhere('name','=',$request['parameter']);

                if ($customer_id == null) {
                    return response()->json([
                        'error'=>true,
                        'message'=>"No such customer exists"
                    ]);

                } else {
                    $loans = DB::table('customer_loan')->where('customer_id','=',$customer_id)->get();

                    return response()->json([
                        'error'=>false,
                        'loans'=>$loans,
                        'customer_id'=>$customer_id
                    ]);
                }

            } catch (\Exception $e) {
                return response()->json([
                    'error'=>true,
                    'message'=>$e->getMessage()
                ]);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkExistingLoan(Request $request) {
        $status = DB::table('customer')->where('id','=',$request['customer_id'])->select('status')->pluck('status')->first();
        if ($status == "ongoing") {
            return response()->json([
                'error'=>false,
                'isOngoing'=>true
            ]);
        } else {
            return response()->json([
                'error'=>false,
                'isOngoing'=>false
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLoanNumber () {
        $loan_no = DB::table('customer_loan')->orderBy('loan_no','desc')->select('loan_no')->pluck('loan_no')->first();

        return response()->json([
            'error'=>false,
            'loan_no'=>$loan_no+1
        ]);
    }

    /**
     * @param $customer_id
     * @return bool
     */
    //when new loan click database should check for existing loan that is ongoing(check customer status)
    private function checkIsActive($customer_id) {
        try {
            $status = DB::table('customer')->where('id','=',$customer_id)->select('status')->pluck('status')->first();

            if($status == "active") {
                return $active = true;
            } elseif($status == "deactive" || $status == "ongoing") {
                return $active = false;
            } else {
                return $active = false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCustomerLoan(Request $request) {
        $validator = Validator::make($request->all(), [
            'loan_no'=>'required',
            'start_date'=>'required',
            'duration'=>'required',
            'end_date'=>'required',
            'salesRep_id'=>'required',
            'commission_rate'=>'required',
            'commission_amount'=>'required',
            'cash_amount'=>'required',
            'bank_amount'=>'required',
            'loan_amount'=>'required',
            'loan_interest'=>'required',
            'payment_days'=>'required',
            'installment_amount'=>'required',
            'total_loan'=>'required',
            'customer_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>$validator->errors()
            ]);
        } else {
            if ($this->checkIsActive($request['customer_id'])) {
                //cash book and bank book add
                $customer_name = DB::table('customer')->where('id','=',$request['customer_id'])->select('name')->pluck('name')->first();
                $cash_withdraw = new Cash_book();
                $cash_withdraw->transaction_date = Carbon::now ()->format ("Y-m-d H:i:s");
                $cash_withdraw->description = "given new loan to ".$customer_name;
                $cash_withdraw->deposit = 0.0;
                $cash_withdraw->withdraw = $request['cash_amount'];
                $last_balance_row = DB::table ( 'cash_book' )->orderBy ( 'id' , 'desc' )->first ();
                if ($last_balance_row){
                    $cash_withdraw->balance = $last_balance_row->balance - $request['cash_amount'];
                }else {
                    $cash_withdraw->balance = -$request['cash_amount'];
                }

                $cash_withdraw->save ();

                $bank_withdraw = new Bank_book();



                $repayment = new Customer_repayment();
                $repayment->loan_id = $loan->id;
                $repayment->bank_book_id = null;
                $repayment->cash_book_id = null;
                $repayment->amount = 0.0;
                $repayment->installment_count = $request->no_of_installments;
                $repayment->remaining_amount = $request->loan_amount;
                $repayment->save ();

                $customer = Customer::where ( 'id' , $request['customer_id'] )->first ();

                if ( $customer ) {
                    $customer->status = "ongoing";
                    $customer->save ();
                }
                //loan add
                //commission add
            } else {
                return response()->json([
                    'error'=>true,
                    'message'=>"This customer already have an ongoing loan"
                ]);
            }
        }
    }

    public function calculateEndDate (Request $request) {
        $start_date = $request['start_date'];

        //getData from calendar table and iterate by removing holidays
        //return end date
    }
}
