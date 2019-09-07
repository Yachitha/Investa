<?php /** @noinspection ALL */

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
            if ($loan_repayments) {
                return response ()->json ([
                    "error"=>FALSE,
                    "repayments"=>$loan_repayments
                ]);
            }else {
                return response ()->json([
                    'error'=>TRUE,
                    'message'=>"repayment already exists for today"
                ]);
            }

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
            if ($loan_repayments) {
                return response ()->json([
                    "error"=>FALSE,
                    "repayments"=>$loan_repayments
                ]);
            }else {
                return response ()->json([
                    'error'=>TRUE,
                    'message'=>"repayment already exists for today"
                ]);
            }

        } else{
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
            if ($loan_repayments){
                return response ()->json([
                    "error"=>FALSE,
                    "repayments"=>$loan_repayments
                ]);
            }else {
                return response ()->json([
                    'error'=>TRUE,
                    'message'=>"repayment already exists for today"
                ]);
            }

        }
    }

    public function repayments_create($loan_id, $cash_id, $bank_id, $amount){


        $last_repayment = Customer_repayment::where('loan_id',$loan_id)->get()->last();
        if (Carbon::parse($last_repayment->created_at)->isToday ()){
            return null;
        }else {
            if ($last_repayment!=null){
                $installment_count = $last_repayment->installment_count;
                $remaining_amount = $last_repayment->remaining_amount;

                $loan_repayments = new Customer_repayment();
                $loan_repayments->loan_id = $loan_id;
                $loan_repayments->bank_book_id = $bank_id;
                $loan_repayments->cash_book_id = $cash_id;
                $loan_repayments->amount = $amount;
                $loan_repayments->installment_count =$installment_count-1;//TODO should be incremental
                $loan_repayments->remaining_amount = $remaining_amount-$amount;
                $loan_repayments->save ();
            }
            else{
                $loan_repayments = new Customer_repayment();
                $loan_repayments->loan_id = $loan_id;
                $loan_repayments->bank_book_id = $bank_id;
                $loan_repayments->cash_book_id = $cash_id;
                $loan_repayments->amount = $amount;
                $loan_repayments->installment_count =$this->no_of_installments-1;//TODO should be incremental
                $loan_repayments->remaining_amount = $this->loan_amount-$amount;
                $loan_repayments->save ();
            }
        }

        return $loan_repayments;
    }

    /**
     * Logic wrong, should go to another spring
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCustomerRepayment(Request $request){
        $id = $request->repayment_id;
        $cash_amount = $request->cash_amount;
        $bank_amount= $request->bank_amount;//amount should validate by the app ===> isRequired
        $cheque_no = $request->cheque_no;
        $date = Carbon::parse ($request->repayment_date);

        try{
            $repayment = Customer_repayment::find($id);
            if ($repayment){
                if (Carbon::parse ($repayment->created_at)->isToday () ){
                    if($id!=1){
                        $last_balance = Cash_book::all ()->last ()->balance;

                        $cash_book_withdraw = new Cash_book();
                        $cash_book_withdraw->transaction_date = Carbon::now ();
                        $cash_book_withdraw->description = "adjustment have been made for loan no ".$repayment->hasCustomerLoan->loan_no;
                        $cash_book_withdraw->deposit = 0.0;
                        $cash_book_withdraw->withdraw = $repayment->hasCashBook->deposit?$repayment->hasCashBook->deposit:0.0;
                        $cash_book_withdraw->balance = $last_balance - ($repayment->hasCashBook->deposit);
                        $cash_book_withdraw->save ();

                        $cash_book_deposit = new Cash_book();
                        $cash_book_deposit->transaction_date =Carbon::now ();
                        $cash_book_deposit->description = "adjustment have been made for loan no ".$repayment->hasCustomerLoan->loan_no;
                        $cash_book_deposit->deposit = $cash_amount;
                        $cash_book_deposit->withdraw = 0.0;
                        $cash_book_deposit->balance = $cash_book_withdraw->balance+($cash_amount);
                        $cash_book_deposit->save ();

                        if($bank_amount>0){
                            $last_balance_bank = Bank_book::all ()->last ()->balance;

                            $bank_book_withdraw = new Bank_book();
                            $bank_book_withdraw->transaction_date = Carbon::now ();
                            $bank_book_withdraw->description = "adjustment have been made for loan no ".$repayment->hasCustomerLoan->loan_no;
                            $bank_book_withdraw->deposit = 0.0;
                            $bank_book_withdraw->withdraw = $repayment->hasBankBook->deposit?$repayment->hasBankBook->deposit:0.0;//should reduce the deposit amount of the repayment first
                            $bank_book_withdraw->balance = $last_balance_bank - ($repayment->hasBankBook->deposit);
                            $bank_book_withdraw->cheque_no = $cheque_no ? $cheque_no: $bank_book_withdraw->cheque_no;
                            $bank_book_withdraw->save ();

                            $bank_book_deposit = new Bank_book();
                            $bank_book_deposit->transaction_date = Carbon::now ();
                            $bank_book_deposit->description = "adjustment have been made for loan no ".$repayment->hasCustomerLoan->loan_no;
                            $bank_book_deposit->deposit = $bank_amount;
                            $bank_book_deposit->withdraw = 0.0;
                            $bank_book_deposit->balance = $bank_book_withdraw->balance + $bank_amount;
                            $bank_book_deposit->cheque_no = $cheque_no ? $cheque_no: $bank_book_withdraw->cheque_no;
                            $bank_book_deposit->save ();

                            $repayment->bank_book_id = $bank_book_deposit->id;
                        }

                        $lastRemainingAmount = $repayment->remaining_amount+$repayment->amount;
                        $repayment->remaining_amount = $lastRemainingAmount - ($cash_amount+$bank_amount);
                        $repayment->cash_book_id = $cash_book_deposit->id;
                    }
                    else{
                        $loan_amount = Customer_repayment::find($id)->hasCustomerLoan->amount;
                        $repayment->remaining_amount = $loan_amount - ($cash_amount+$bank_amount);
                    }
                    $repayment->amount = $cash_amount+$bank_amount;//case-same date
                    $repayment->save();

                    return response ()->json ([
                        'error'=>false,
                        'repayments'=>$repayment
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
