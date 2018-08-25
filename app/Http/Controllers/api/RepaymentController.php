<?php

namespace App\Http\Controllers\api;

use App\Bank_book;
use App\Cash_book;
use App\Customer_loan;
use App\Customer_repayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Whoops\Exception\ErrorException;

class RepaymentController extends Controller
{
    /**
     * The good method is to create separate cash_book controller and use class object injection to get to use method
     * in this controller
     */
    private $loan_amount;
    private $no_of_installments;

    public function customerRepayments(Request $request){
        $loan_id = $request->loan_id;
        $cash_amount = $request->cash_amount;
        $bank_amount = $request->bank_amount;
        $cheque_no = $request->cheque_no;

        $customer_loan = Customer_loan::find($loan_id);
        $customer_name = $customer_loan->hasCustomer->name;
        $this->loan_amount = $customer_loan->loan_amount;
        $this->no_of_installments = $customer_loan->no_of_installments;

        if ($cash_amount>0 && $bank_amount>0){
            $cash_last_balance = Cash_book::all ()->last()->balance;

            $cash_book = new Cash_book();
            $cash_book->transaction_date = Carbon::now ();
            $cash_book->description = $customer_name." repayments to ".$customer_loan->loan_no;
            $cash_book->deposit = $cash_amount;
            $cash_book->withdraw = 0;
            $cash_book->balance = $cash_last_balance+$cash_amount;
            $cash_book->save ();

            $cash_book_id = $cash_book->id;

            $bank_last_balance = Bank_book::all ()->last ()->balance;

            $bank_book = new Bank_book();
            $bank_book->transaction_date = Carbon::now ();
            $bank_book->description = $customer_name." repayments to ".$customer_loan->loan_no." by cheque number ".$cheque_no;
            $bank_book->deposit = $bank_amount;
            $bank_book->withdraw = 0;
            $bank_book->balance = $bank_last_balance+$bank_amount;
            $bank_book->cheque_no = $cheque_no;
            $bank_book->save ();

            $bank_book_id = $bank_book->id;

            $loan_repayments = $this->repayments_create ($loan_id, $cash_book_id, $bank_book_id, $cash_amount+$bank_amount);
            return response ()->json ([
               "error"=>FALSE,
               "repayments"=>$loan_repayments
            ]);
        }elseif ($cash_amount>0){
            $cash_last_balance = Cash_book::all ()->last()->balance;

            $cash_book = new Cash_book();
            $cash_book->transaction_date = Carbon::now ();
            $cash_book->description = $customer_name." repayments to ".$customer_loan->loan_no;
            $cash_book->deposit = $cash_amount;
            $cash_book->withdraw = 0;
            $cash_book->balance = $cash_last_balance+$cash_amount;
            $cash_book->save ();

            $cash_book_id = $cash_book->id;

            $loan_repayments = $this->repayments_create ($loan_id, $cash_book_id, null, $cash_amount);
            return response ()->json([
               "error"=>FALSE,
               "repayments"=>$loan_repayments
            ]);
        }else{
            $bank_last_balance = Bank_book::all ()->last ()->balance;

            $bank_book = new Bank_book();
            $bank_book->transaction_date = Carbon::now ();
            $bank_book->description = $customer_name." repayments to ".$customer_loan->loan_no." by cheque number ".$cheque_no;
            $bank_book->deposit = $bank_amount;
            $bank_book->withdraw = 0;
            $bank_book->balance = $bank_last_balance+$bank_amount;
            $bank_book->cheque_no = $cheque_no;
            $bank_book->save ();

            $bank_book_id = $bank_book->id;

            $loan_repayments = $this->repayments_create ($loan_id, null, $bank_book_id, $bank_amount);
            return response ()->json([
                "error"=>FALSE,
                "repayments"=>$loan_repayments
            ]);
        }
    }

    public function repayments_create($loan_id, $cash_id, $bank_id, $amount){


        $last_repayment = Customer_repayment::where('loan_id',$loan_id)->get()->last();
        if ($last_repayment!=null){
            $installment_count = $last_repayment->installment_count;
            $remaining_amount = $last_repayment->remaining_amount;

            $loan_repayments = new Customer_repayment();
            $loan_repayments->loan_id = $loan_id;
            $loan_repayments->bank_book_id = $bank_id;
            $loan_repayments->cash_book_id = $cash_id;
            $loan_repayments->amount = $amount;
            $loan_repayments->installment_count =$installment_count-1;
            $loan_repayments->remaining_amount = $remaining_amount-$amount;
            $loan_repayments->save ();
        }
        else{
            $loan_repayments = new Customer_repayment();
            $loan_repayments->loan_id = $loan_id;
            $loan_repayments->bank_book_id = $bank_id;
            $loan_repayments->cash_book_id = $cash_id;
            $loan_repayments->amount = $amount;
            $loan_repayments->installment_count =$this->no_of_installments-1;
            $loan_repayments->remaining_amount = $this->loan_amount-$amount;
            $loan_repayments->save ();
        }

        return $loan_repayments;
    }

    /**
     * Logic wrong, should go to another spring
     */
    public function editCustomerRepayment(Request $request){
        $id = $request->repayment_id;
        $cash_amount = $request->cash_amount;
        $bank_amount= $request->bank_amount;//amount should validate by the app ===> isRequired
        //$cheque_no = $request->cheque_no;
        $date = Carbon::parse ($request->repayment_date);

        try{
            $repayment = Customer_repayment::find($id);
            if ($repayment){
                if (Carbon::parse ($repayment->created_at)->isSameDay ($date) ){
                    if($id!=1){
                        $last_balance = Cash_book::all ()->last ()->balance;

                        $cash_book_withdraw = new Cash_book();
                        $cash_book_withdraw->transaction_date = Carbon::now ();
                        $cash_book_withdraw->description = Auth::user ()->name." adjustment have been made ".Customer_loan::find($repayment->loan_id)->loan_no;
                        $cash_book_withdraw->deposit = 0;
                        $cash_book_withdraw->withdraw = $repayment->amount;
                        $cash_book_withdraw->balance = $last_balance - ($repayment->amount);
                        $cash_book_withdraw->save ();

                        $cash_book_deposit = new Cash_book();
                        $cash_book_deposit->transaction_date =Carbon::now ();
                        $cash_book_deposit->description = Auth::user ()->name." adjustment have been made ".Customer_loan::find($repayment->loan_id)->loan_no;
                        $cash_book_deposit->deposit = $cash_amount+$bank_amount;
                        $cash_book_deposit->withdraw = 0;
                        $cash_book_deposit->balance = $cash_book_withdraw->balance+($cash_amount+$bank_amount);
                        $cash_book_deposit->save ();

                        $lastRemainingAmount = Customer_repayment::find($id-1)->remaining_amount;//wrong
                        $repayment->remaining_amount = $lastRemainingAmount - ($cash_amount+$bank_amount);
                    }
                    else{
                        $loan_amount = Customer_repayment::find($id)->hasLoan->amount;
                        $repayment->remaining_amount = $loan_amount - ($cash_amount+$bank_amount);
                    }
                    $repayment->amount = $cash_amount+$bank_amount;//case-same date
//                    if($bank_amount){
//                        $repayment->cheque_no = $cheque_no;
//                    }
                    $repayment->save();

                    return response ()->json ([
                        'error'=>false,
                        'repayment'=>$repayment
                    ]);
                }else{
                    //case-not the same date
                    return response ()->json ([
                        'error'=>true,
                        'message'=>"Edit must done in same date"
                    ]);
                }
            }else{
                //case-no repayment exists
                return response ()->json ([
                    'error'=>TRUE,
                    'message'=>"no repayment exists with given id"
                ]);
            }
        }catch (ModelNotFoundException $modelNotFoundException){
            return response ()->json ([
                'error'=>TRUE,
                'message'=>"Model not found",
            ]);
        }
    }
}
