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
use App\Customer_loan;
use App\Customer_repayment;
use App\Http\Controllers\Customers\CustomerController;
use App\SalesRepCommission;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerLoanController
{

    protected $customerController;

    public function __construct(CustomerController $controller)
    {
        $this->customerController = $controller;
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getLoanDetailsForCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parameter' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ]);
        } else {
            try {
                $customer_id = DB::table('customer')->where('customer_no', '=', $request['parameter'])
                    ->orWhere('name', '=', $request['parameter'])->select('id')->pluck('id')->first();

                if ($customer_id == null) {
                    return response()->json([
                        'error' => true,
                        'message' => "No such customer exists"
                    ]);

                } else {
                    $loans = DB::table('customer_loan')->where('customer_id', '=', $customer_id)->get();
                    $salesReps = DB::table('users')->get();

                    foreach ($loans as $loan) {
                        $loan->due_amount = $this->getDueAmount($loan->id, $loan->total_loan_amount);
                        $loan->commission = $this->getLoanCommission($loan->id);
                        $loan->cash_amount = $this->getCashAmount($loan->cash_book_id);
                        $loan->bank_amount = $this->getBankAmount($loan->bank_book_id);
                        $loan->selectedTypeId = $this->setType($loan->cash_book_id,$loan->bank_book_id);
                    }

                    return response()->json([
                        'error' => false,
                        'loans' => $loans,
                        'customer_id' => $customer_id,
                        'salesReps' => $salesReps,
                    ]);
                }

            } catch (Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * @param $cash_book_id
     * @param $bank_book_id
     * @return int
     */

    private function setType($cash_book_id,$bank_book_id) {
        if ($cash_book_id!=null && $bank_book_id!=null) {
            return 3;
        } elseif ($cash_book_id!=null) {
            return 1;
        } else {
            return 2;
        }
    }

    /**
     * @param $cash_book_id
     * @return int|mixed
     */

    private function getCashAmount($cash_book_id) {
        if ($cash_book_id!=null) {
            $amount = DB::table('cash_book')->where('id',$cash_book_id)->select('withdraw')->pluck('withdraw')->first();
        } else {
            $amount = 0;
        }
        return $amount;
    }

    /**
     * @param $bank_book_id
     * @return int|mixed
     */

    private function getBankAmount($bank_book_id) {
        if($bank_book_id!=null) {
            $amount = DB::table('bank_book')->where('id',$bank_book_id)->select('withdraw')->pluck('withdraw')->first();
        } else {
            $amount = 0;
        }
        return $amount;
    }

    /**
     * To get due_amount of a given loan
     * @param $loan_id
     * @param $totalLoan
     * @return mixed|string
     */
    private function getDueAmount($loan_id, $totalLoan)
    {
        try {
            $totalPaid = DB::table('customer_loan_repayment')->where('loan_id', '=', $loan_id)->sum('amount');
            return $totalLoan - $totalPaid;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     *
     * @param $loan_id
     * @return Collection
     */
    private function getLoanCommission($loan_id)
    {
        try {
            $commission = DB::table('sales_rep_commission')->where('loan_id', '=', $loan_id)->get();

            return $commission;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checkExistingLoan(Request $request)
    {
        $status = DB::table('customer')->where('id', '=', $request['customer_id'])->select('status')->pluck('status')->first();
        if ($status == "ongoing") {
            return response()->json([
                'error' => false,
                'isOngoing' => true
            ]);
        } else {
            return response()->json([
                'error' => false,
                'isOngoing' => false
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getLoanNumber()
    {
        $loan_no = DB::table('customer_loan')->orderBy('loan_no', 'desc')->select('loan_no')->pluck('loan_no')->first();

        return response()->json([
            'error' => false,
            'loan_no' => $loan_no + 1
        ]);
    }

    /**
     * @param $customer_id
     * @return bool
     */
    //when new loan click database should check for existing loan that is ongoing(check customer status)
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addCustomerLoan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_no' => 'required',
            'start_date' => 'required',
            'duration' => 'required',
            'end_date' => 'required',
            'salesRep_id' => 'required',
            'commission_rate' => 'required',
            'cash_amount' => 'required',
            'bank_amount' => 'required',
            'loan_interest' => 'required',
            'payment_days' => 'required',
            'customer_id' => 'required',
            'cheque_no' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ]);
        } else {
            if ($this->checkIsActive($request['customer_id'])) {
                $loan_no = $request['loan_no'];
                $start_date = $request['start_date'];
                $duration = $request['duration'];
                $end_date = $request['end_date'];
                $salesRep_id = $request['salesRep_id'];
                $commission_rate = $request['commission_rate'];
                $cash_amount = (float) $request['cash_amount'];
                $bank_amount = (float) $request['bank_amount'];
                $loan_interest = $request['loan_interest'];
                $payment_days = $request['payment_days'];
                $customer_id = $request['customer_id'];
                $cheque_no = $request['cheque_no'];

                $customer_name = DB::table('customer')->where('id', '=', $customer_id)->select('name')->pluck('name')->first();
                $cash_withdraw_id = null;
                $bank_withdraw_id = null;
                if ($cash_amount > 0) {
                    $cash_withdraw_id = $this->addCashBookRecord($customer_name, $cash_amount);
                }
                if ($bank_amount > 0) {
                    $bank_withdraw_id = $this->addBankBookRecord($customer_name, $bank_amount, $cheque_no);
                }

                $loan_amount = $this->calculateLoanAmount($cash_amount, $bank_amount);
                $total_loan_amount = $this->totalLoanAmount($cash_amount, $bank_amount, $duration, $loan_interest);
                $installment_amount = $this->calculateInstallmentAmount($total_loan_amount, $duration);

                $customer_loan_id = null;
                if ($cash_withdraw_id != null || $bank_withdraw_id != null) {
                    $customer_loan_id = $this->addLoan($loan_no, $loan_interest, $loan_amount, $total_loan_amount, $installment_amount, $payment_days, $start_date, $end_date, $duration, $customer_id, $cash_withdraw_id, $bank_withdraw_id, $cheque_no);
                }

                $customer = Customer::where('id', $request['customer_id'])->first();

                if ($customer && $customer_loan_id!=null) {
                    $customer->status = "ongoing";
                    $customer->save();
                    $this->addInitialRepaymentColumn($customer_loan_id, $payment_days, $total_loan_amount);
                    $this->addSalesRepCommission($commission_rate, $loan_amount, Carbon::now(), $customer_loan_id, $salesRep_id);
                }

            } else {
                return response()->json([
                    'error' => true,
                    'message' => "This customer already have an ongoing loan"
                ]);
            }

            return response()->json([
                'error'=>false,
                'customer_loan_id' => $customer_loan_id,
                'loan_amount' => $loan_amount,
                'total_loan_amount' => $total_loan_amount,
                'installment_amount' => $installment_amount,
            ]);
        }
    }

    /**
     * @param $customer_id
     * @return bool
     */

    private function checkIsActive($customer_id)
    {
        try {
            $status = DB::table('customer')->where('id', '=', $customer_id)->select('status')->pluck('status')->first();

            if ($status == "active") {
                return $active = true;
            } elseif ($status == "Disable" || $status == "ongoing") {
                return $active = false;
            } else {
                return $active = false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $customer_name
     * @param $cash_amount
     * @return mixed|null
     */

    private function addCashBookRecord($customer_name, $cash_amount)
    {
        try {
            $cash_withdraw = new Cash_book();
            $cash_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $cash_withdraw->description = "given new loan to " . $customer_name;
            $cash_withdraw->deposit = 0.0;
            $cash_withdraw->withdraw = $cash_amount;
            $last_balance_row = DB::table('cash_book')->orderBy('id', 'desc')->first();
            if ($last_balance_row) {
                $cash_withdraw->balance = $last_balance_row->balance - $cash_amount;
            } else {
                $cash_withdraw->balance = -$cash_amount;
            }

            $cash_withdraw->save();
            return $cash_withdraw->id;

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $customer_name
     * @param $bank_amount
     * @param $cheque_no
     * @return mixed|null
     */

    private function addBankBookRecord($customer_name, $bank_amount, $cheque_no)
    {
        try {
            $bank_withdraw = new Bank_book();
            $bank_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $bank_withdraw->description = "given new loan to " . $customer_name;
            $bank_withdraw->deposit = 0.0;
            $bank_withdraw->withdraw = $bank_amount;
            $last_balance_row = DB::table('bank_book')->orderBy('id', 'desc')->first();
            if ($last_balance_row) {
                $bank_withdraw->balance = $last_balance_row->balance - $bank_amount;
            } else {
                $bank_withdraw->balance = -$bank_amount;
            }
            $bank_withdraw->cheque_no = $cheque_no;

            $bank_withdraw->save();

            return $bank_withdraw->id;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $cashAmount
     * @param $bankAmount
     * @return mixed
     */

    private function calculateLoanAmount($cashAmount, $bankAmount)
    {
        return $cashAmount + $bankAmount;
    }

    /**
     * @param $cashAmount
     * @param $bankAmount
     * @param $duration
     * @param $rate
     * @return float|int|mixed
     */

    private function totalLoanAmount($cashAmount, $bankAmount, $duration, $rate)
    {
        return $this->calculateLoanAmount($cashAmount, $bankAmount) + ($cashAmount + $bankAmount) * ($rate / 30) * ($duration / 100);
    }

    /**
     * @param $totalLoan
     * @param $duration
     * @return float|int
     */

    private function calculateInstallmentAmount($totalLoan, $duration)
    {
        return $totalLoan / $duration;
    }

    /**
     * @param $loan_no
     * @param $loan_interest
     * @param $loan_amount
     * @param $total_loan_amount
     * @param $installment_amount
     * @param $payment_days
     * @param $start_date
     * @param $end_date
     * @param $duration
     * @param $customer_id
     * @param $cash_withdraw_id
     * @param $bank_withdraw_id
     * @return mixed|null
     */

    private function addLoan($loan_no, $loan_interest, $loan_amount, $total_loan_amount, $installment_amount, $payment_days, $start_date, $end_date, $duration, $customer_id, $cash_withdraw_id, $bank_withdraw_id, $cheque_no)
    {
        try {
            $customer_loan = new Customer_loan();
            $customer_loan['loan_no'] = $loan_no;
            $customer_loan['interest_rate'] = $loan_interest;
            $customer_loan['loan_amount'] = $loan_amount;
            $customer_loan['total_loan_amount'] = $total_loan_amount;
            $customer_loan['installment_amount'] = $installment_amount;
            $customer_loan['no_of_installments'] = $payment_days;
            $customer_loan['start_date'] = $start_date;
            $customer_loan['end_date'] = $end_date;
            $customer_loan['duration'] = $duration;
            $customer_loan['customer_id'] = $customer_id;
            $customer_loan['cash_book_id'] = $cash_withdraw_id;
            $customer_loan['bank_book_id'] = $bank_withdraw_id;
            $customer_loan['isFinished'] = false;
            $customer_loan['isArrears'] = false;
            $customer_loan['due_amount'] = $total_loan_amount;
            $customer_loan['arrears_amount'] = 0.00;
            $customer_loan['type'] = "cash";
            $customer_loan['cheque_no'] = $cheque_no;
            $customer_loan['arrears_next'] = false;

            $customer_loan->save();

            return $customer_loan->id;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $customer_loan_id
     * @param $no_of_installments
     * @param $total_loan_amount
     * @return mixed|null
     */

    private function addInitialRepaymentColumn($customer_loan_id, $no_of_installments, $total_loan_amount)
    {
        try {
            $repayment = new Customer_repayment();
            $repayment->loan_id = $customer_loan_id;
            $repayment->bank_book_id = null;
            $repayment->cash_book_id = null;
            $repayment->amount = 0.0;
            $repayment->installment_count = 0;//TODO should be incremental. set this to 1 or 0 appropriately=>set to 0
            $repayment->remaining_amount = $total_loan_amount;
            $repayment->save();

            return $repayment->id;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $commission_rate
     * @param $loan_amount
     * @param $date
     * @param $loan_id
     * @param $user_id
     * @return mixed|null
     */

    private function addSalesRepCommission($commission_rate, $loan_amount, $date, $loan_id, $user_id)
    {
        try {
            $commission = new SalesRepCommission();
            $commission['commission_rate'] = $commission_rate;
            $commission['commission_amount'] = ($loan_amount * $commission_rate) / 100;
            $commission['date'] = $date;
            $commission['loan_id'] = $loan_id;
            $commission['user_id'] = $user_id;

            $commission->save();

            return $commission->id;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * when start_date and duration given end_date of the loan will be calculate
     * @param Request $request
     * @return JsonResponse
     */
    public function calculateEndDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'duration' => 'required|int'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ]);
        } else {
            $start_date = Carbon::parse($request['start_date']);
            $duration = $request['duration'];
            $calendarDates = DB::table('calendar')->get();

            while ($duration > 0) {
                $holiday = $calendarDates->filter(function ($item) use ($start_date) {
                    if ($start_date->eq(Carbon::parse($item->date))) {
                        return true;
                    } else {
                        return false;
                    }
                });

                if (!$holiday->isEmpty()) {
                    $duration++;
                }

                $start_date->addDay(1);
                $duration--;
            }

            return response()->json([
                'end_date' => $start_date->format('Y-m-d')
            ]);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function getBasicLoanDetails(Request $request)
    {
        $cashAmount = $request['cash_amount'];
        $bankAmount = $request['bank_amount'];
        $duration = $request['duration'];
        $rate = $request['interest_rate'];

        $totalAmount = $this->totalLoanAmount($cashAmount, $bankAmount, $duration, $rate);

        $installment_amount = $this->calculateInstallmentAmount($totalAmount, $duration);

        return response()->json([
            'total_amount' => $totalAmount,
            'installment' => round($installment_amount, 2)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function editCustomerLoan(Request $request)
    {
        $customer_id = $request['customer_id'];
        $loan_id = $request['loan_id'];
        $start_date = $request['start_date'];//directly to db
        $duration = $request['duration'];//directly to db
        $end_date = $request['end_date'];//directly to db
        $salesRep_id = $request['salesRep_id'];
        $commission_rate = $request['commission_rate'];
        $cash_amount = $request['cash_amount'];
        $bank_amount = $request['bank_amount'];
        $loan_interest = $request['loan_interest'];
        $payment_days = $request['payment_days'];
        $cheque_no = $request['cheque_no'];

        $new_cash_book_id = null;
        $new_bank_book_id = null;

        if ($loan_id) {
            $loan = DB::table('customer_loan')->where('id', $loan_id)->first();
            $customer_name = DB::table('customer')->where('id', $customer_id)->select('name')->pluck('name')->first();
            if (!empty($loan)) {
                if ($loan->cash_book_id!=null) {
                    $cash_book_id = $loan->cash_book_id;
                    $new_cash_book_id = $this->createAdjustmentToCashBook($cash_book_id, $customer_name, $cash_amount);
                }
                if ($loan->bank_book_id!=null) {
                    $bank_book_id = $loan->bank_book_id;
                    $new_bank_book_id = $this->createAdjustmentToBankBook($bank_book_id, $customer_name, $bank_amount, $cheque_no);
                }
                $new_commission = $this->editSalesRepCommission($loan_id,$commission_rate,$salesRep_id,$this->calculateLoanAmount($cash_amount,$bank_amount));
                $new_initial_repayment = $this->editInitialRepayment($loan_id,0,$this->calculateLoanAmount($cash_amount,$bank_amount));
                $total_loan_amount = $this->totalLoanAmount($cash_amount,$bank_amount,$duration,$loan_interest);
                $updated_loan = $this->editLoanDetails($loan_id,$loan_interest,$this->calculateLoanAmount($cash_amount,$bank_amount),$total_loan_amount,$this->calculateInstallmentAmount($total_loan_amount,$duration),$payment_days,$start_date,$end_date,$duration,$customer_id,$new_cash_book_id,$new_bank_book_id,$cheque_no);
                $updated_loan->due_amount = $this->getDueAmount($updated_loan->id, $total_loan_amount);
                $updated_loan->commission = $this->getLoanCommission($updated_loan->id);
                $updated_loan->cash_amount = $this->getCashAmount($updated_loan->cash_book_id);
                $updated_loan->bank_amount = $this->getBankAmount($updated_loan->bank_book_id);
                $updated_loan->selectedTypeId = $this->setType($updated_loan->cash_book_id,$updated_loan->bank_book_id);

                return response()->json([
                    'error'=>false,
                    'updated_commission'=>$new_commission,
                    'new_initial_repayment'=>$new_initial_repayment,
                    'updated_loan'=>$updated_loan
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Loan was deleted"
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => "empty loan id"
            ]);
        }
    }

    /**
     * @param $old_entry_id
     * @param $customer_name
     * @param $cash_amount
     * @return mixed|null
     */

    private function createAdjustmentToCashBook($old_entry_id, $customer_name, $cash_amount)
    {
        $old_amount = DB::table('cash_book')->where('id', $old_entry_id)->select('withdraw')->pluck('withdraw')->first();
        if ($old_amount != $cash_amount) {
            try {
                $cash_deposit = new Cash_book();
                $cash_deposit->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
                $cash_deposit->description = "adjustment to loan to " . $customer_name;
                $cash_deposit->deposit = $old_amount;
                $cash_deposit->withdraw = 0.0;
                $last_balance_row = DB::table('cash_book')->orderBy('id', 'desc')->first();
                if ($last_balance_row) {
                    $cash_deposit->balance = $last_balance_row->balance + $old_amount;
                } else {
                    $cash_deposit->balance = +$old_amount;
                }

                $cash_deposit->save();

                $cash_withdraw = new Cash_book();
                $cash_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
                $cash_withdraw->description = "adjustment to loan to " . $customer_name;
                $cash_withdraw->deposit = 0.0;
                $cash_withdraw->withdraw = $cash_amount;
                $last_balance_row = DB::table('cash_book')->orderBy('id', 'desc')->first();
                if ($last_balance_row) {
                    $cash_withdraw->balance = $last_balance_row->balance - $cash_amount;
                } else {
                    $cash_withdraw->balance = -$cash_amount;
                }

                $cash_withdraw->save();

                return $cash_withdraw->id;
            } catch (Exception $e) {
                return null;
            }
        } else {
            return $old_entry_id;
        }
    }

    /**
     * @param $old_entry_id
     * @param $customer_name
     * @param $bank_amount
     * @param $cheque_no
     * @return mixed|null
     */

    private function createAdjustmentToBankBook($old_entry_id, $customer_name, $bank_amount, $cheque_no)
    {
        $old_amount = DB::table('bank_book')->where('id', $old_entry_id)->select('withdraw')->pluck('withdraw')->first();
        $old_cheque_no = DB::table('bank_book')->where('id', $old_entry_id)->select('cheque_no')->pluck('cheque_no')->first();
        if ($old_amount != $bank_amount) {
            try {
                $bank_deposit = new Bank_book();
                $bank_deposit->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
                $bank_deposit->description = "adjustment to loan to " . $customer_name . " cheque number: " . $old_cheque_no;
                $bank_deposit->deposit = $old_amount;
                $bank_deposit->withdraw = 0.0;
                $last_balance_row = DB::table('bank_book')->orderBy('id', 'desc')->first();
                if ($last_balance_row) {
                    $bank_deposit->balance = $last_balance_row->balance + $old_amount;
                } else {
                    $bank_deposit->balance = +$old_amount;
                }
                $bank_deposit->cheque_no = $old_cheque_no;
                $bank_deposit->save();

                $bank_withdraw = new Bank_book();
                $bank_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
                $bank_withdraw->description = "adjustment to loan to " . $customer_name . " cheque number: " . $cheque_no;
                $bank_withdraw->deposit = 0.0;
                $bank_withdraw->withdraw = $bank_amount;
                $last_balance_row = DB::table('bank_book')->orderBy('id', 'desc')->first();
                if ($last_balance_row) {
                    $bank_withdraw->balance = $last_balance_row->balance - $bank_amount;
                } else {
                    $bank_withdraw->balance = -$bank_amount;
                }
                $bank_withdraw->cheque_no = $cheque_no;
                $bank_withdraw->save();

                return $bank_withdraw->id;
            } catch (Exception $e) {
                return null;
            }
        } else {
            return $old_entry_id;
        }
    }

    /**
     * @param $loan_id
     * @param $commission_rate
     * @param $salesRep_id
     * @param $loan_amount
     * @return Model|Builder|object|null
     */

    private function editSalesRepCommission($loan_id, $commission_rate, $salesRep_id, $loan_amount) {
        try{
            DB::table('sales_rep_commission')->where('loan_id','=',$loan_id)->update([
                'commission_rate'=>$commission_rate,
                'commission_amount'=>($loan_amount * $commission_rate) / 100,
                'date'=>Carbon::now(),
                'user_id'=>$salesRep_id
            ]);

            $commission = DB::table('sales_rep_commission')->where('loan_id','=',$loan_id)->first();

            return $commission;
        } catch(Exception $e) {
            return null;
        }
    }

    /**
     * @param $loan_id
     * @param $installment_count
     * @param $loan_amount
     * @return Model|Builder|object|null
     */

    private function editInitialRepayment($loan_id,$installment_count,$loan_amount) {
        try{
            DB::table('customer_loan_repayment')->where('loan_id',$loan_id)->orderBy('id','asc')->take(1)->update([
                'installment_count'=>$installment_count,
                'remaining_amount'=>$loan_amount
            ]);

            $installment = DB::table('customer_loan_repayment')->where('loan_id',$loan_id)->orderBy('id','asc')->first();

            return $installment;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $loan_id
     * @param $loan_interest
     * @param $loan_amount
     * @param $total_loan_amount
     * @param $installment_amount
     * @param $payment_days
     * @param $start_date
     * @param $end_date
     * @param $duration
     * @param $customer_id
     * @param $cash_withdraw_id
     * @param $bank_withdraw_id
     * @param $cheque_no
     * @return Model|Builder|object|null
     */

    private function editLoanDetails($loan_id, $loan_interest, $loan_amount, $total_loan_amount, $installment_amount, $payment_days, $start_date, $end_date, $duration, $customer_id, $cash_withdraw_id, $bank_withdraw_id,$cheque_no) {
        try{
            DB::table('customer_loan')->where('id',$loan_id)->update([
                'interest_rate'=>$loan_interest,
                'loan_amount'=>$loan_amount,
                'total_loan_amount'=>$total_loan_amount,
                'installment_amount'=>$installment_amount,
                'no_of_installments'=>$payment_days,
                'start_date'=>$start_date,
                'end_date'=>$end_date,
                'duration'=>$duration,
                'cash_book_id'=>$cash_withdraw_id,
                'bank_book_id'=>$bank_withdraw_id,
                'due_amount'=>$total_loan_amount,
                'cheque_no'=>$cheque_no
            ]);

            $updated_loan = DB::table('customer_loan')->where('id',$loan_id)->first();

            return $updated_loan;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function deleteCustomerLoan(Request $request) {
        $loan_id = $request['loan_id'];
        $salesRep_id = $request['salesRep_id'];
        $customer_id = $request['customer_id'];
        $isDeletePermanently = $request['isDeletePermanently'];

        if($loan_id) {
            if ($isDeletePermanently==true) {
                $response = $this->deletePermanently($loan_id,$salesRep_id,$customer_id);

                return response()->json([
                    'error'=>false,
                    'message'=>$response
                ]);
            } else {
                $response = $this->softDeleteLoan($loan_id,$salesRep_id,$customer_id);

                return response()->json([
                    'error'=>false,
                    'message'=>$response
                ]);
            }
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"no loan specified!"
            ]);
        }
    }

    /**
     * @param $loan_id
     * @param $salesRep_id
     * @param $customer_id
     * @return string
     */
    private function deletePermanently($loan_id, $salesRep_id, $customer_id) {
        $loan_exists = DB::table('customer_loan')->where('id','=',$loan_id)->exists();
        if($loan_exists) {
            $loan = DB::table('customer_loan')->where('id','=',$loan_id)->first();
            $customer = DB::table('customer')->where('id','=',$customer_id)->first();
            $cash_book_id = $loan->cash_book_id;
            $bank_book_id = $loan->bank_book_id;
            if ($cash_book_id!=null) {
                $this->cashBookDeletionEntry($cash_book_id,$customer->name,true);
            }
            if ($bank_book_id!=null) {
                $this->bankBookDeletionEntry($bank_book_id,$customer->name,true);
            }
            $commission_delete = DB::table('sales_rep_commission')->where('loan_id','=',$loan_id)->delete();
            $repayment_delete = DB::table('customer_loan_repayment')->where('loan_id','=',$loan_id)->delete();
            $loan_delete = DB::table('customer_loan')->delete($loan_id);
            $update_customer = DB::table('customer')->where('id','=',$customer_id)->update([
                'status'=>"active"
            ]);

            if ($commission_delete && $repayment_delete && $loan_delete && $update_customer) {
                return "Permanently deleted the specified loan";
            }
        } else {
            return "Failed to delete";
        }
    }

    /**
     * @param $loan_id
     * @param $salesRep_id
     * @param $customer_id
     * @return string
     */
    private function softDeleteLoan($loan_id,$salesRep_id,$customer_id) {
        $loan_exists = DB::table('customer_loan')->where('id','=',$loan_id)->exists();
        if($loan_exists) {
            $loan = DB::table('customer_loan')->where('id','=',$loan_id)->first();
            $customer = DB::table('customer')->where('id','=',$customer_id)->first();
            $cash_book_id = $loan->cash_book_id;
            $bank_book_id = $loan->bank_book_id;
            if ($cash_book_id!=null) {
                $this->cashBookDeletionEntry($cash_book_id,$customer->name,false);
            }
            if ($bank_book_id!=null) {
                $this->bankBookDeletionEntry($bank_book_id,$customer->name,false);
            }
            $commission_delete_count = DB::table('sales_rep_commission')->where('loan_id','=',$loan_id)->update([
                'deleted_at'=>Carbon::now()
            ]);
            $repayment_delete_count = DB::table('customer_loan_repayment')->where('loan_id','=',$loan_id)->update([
                'deleted_at'=>Carbon::now()
            ]);
            $loan_delete_count = DB::table('customer_loan')->where('id','=',$loan_id)->update([
                'deleted_at'=>Carbon::now()
            ]);
            $update_customer_count = DB::table('customer')->where('id','=',$customer_id)->update([
                'status'=>"active"
            ]);

            if ($commission_delete_count>0 && $repayment_delete_count>0 && $loan_delete_count>0 && $update_customer_count>0) {
                return "deleted the specified loan";
            }
        } else {
            return "Failed to delete";
        }
    }

    /**
     * @param $loan_id
     * @param $customer_name
     * @param $isDeletePermanently
     * @return mixed|null
     */
    private function cashBookDeletionEntry($loan_id, $customer_name, $isDeletePermanently) {
        try {
            $remaining_amount = DB::table('customer_loan_repayment')->where('loan_id', $loan_id)->orderBy('id','desc')->select('remaining_amount')->pluck('remaining_amount')->first();

            $cash_deletion_entry = new Cash_book();
            $cash_deletion_entry->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            if ($isDeletePermanently) {
                $cash_deletion_entry->description = "permanently deleted the loan to " . $customer_name;
            } else{
                $cash_deletion_entry->description = "deleted the loan to " . $customer_name;
            }
            $cash_deletion_entry->deposit = $remaining_amount;
            $cash_deletion_entry->withdraw = 0.0;
            $last_balance_row = DB::table('cash_book')->orderBy('id', 'desc')->first();
            if ($last_balance_row) {
                $cash_deletion_entry->balance = $last_balance_row->balance + $remaining_amount;
            } else {
                $cash_deletion_entry->balance = +$remaining_amount;
            }

            $cash_deletion_entry->save();

            return $cash_deletion_entry->id;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $loan_id
     * @param $customer_name
     * @param $isDeletePermanently
     * @return mixed|null
     */
    private function bankBookDeletionEntry($loan_id, $customer_name, $isDeletePermanently) {
        $remaining_amount = DB::table('customer_loan_repayment')->where('loan_id', $loan_id)->orderBy('id','desc')->select('remaining_amount')->pluck('remaining_amount')->first();
        $old_cheque_no = DB::table('bank_book')->where('id', $loan_id)->select('cheque_no')->pluck('cheque_no')->first();

        try{
            $bank_deletion_entry = new Bank_book();
            $bank_deletion_entry->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            if ($isDeletePermanently){
                $bank_deletion_entry->description = "permanently delete the loan to " . $customer_name . " under cheque number: " . $old_cheque_no;
            } else{
                $bank_deletion_entry->description = "delete the loan to " . $customer_name . " under cheque number: " . $old_cheque_no;
            }
            $bank_deletion_entry->deposit = $remaining_amount;
            $bank_deletion_entry->withdraw = 0.0;
            $last_balance_row = DB::table('bank_book')->orderBy('id', 'desc')->first();
            if ($last_balance_row) {
                $bank_deletion_entry->balance = $last_balance_row->balance + $remaining_amount;
            } else {
                $bank_deletion_entry->balance = +$remaining_amount;
            }
            $bank_deletion_entry->cheque_no = $old_cheque_no;
            $bank_deletion_entry->save();

            return $bank_deletion_entry->id;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    //TODO complete the function as update due_amount in customer_loan as well as updating remaining_amount in customer_loan_repayment
    public function clearCustomerLoan(Request $request) {
        $loan_id = $request['loan_id'];
        $loan = DB::table('customer_loan')->where('id','=',$loan_id)->first();
        $customer = DB::table('customer')->where('id','=',$loan->customer_id)->first();
        $customer_id = $customer->id;
        $customer_no = $customer->customer_no;
        $last_repayment = DB::table('customer_loan_repayment')->where('loan_id','=',$loan_id)->get()->last();
        $last_row_bank = DB::table('bank_book')->orderBy('id', 'desc')->first();
        $last_row_cash = DB::table('cash_book')->orderBy('id', 'desc')->first();
        $bank_book_id = null;
        $cash_book_id = null;
        if(!empty($loan)) {
            if ($last_repayment->remaining_amount>0) {
                if($loan->cash_book_id!=null) {
                    $cash_book_id = $this->addLoanClearCashBookEntry($loan->loan_no,$customer_no,$last_repayment->remaining_amount,$last_row_cash->balance);
                }
                if($loan->bank_book_id!=null) {
                    $bank_book_id = $this->addLoanClearBankBookEntry($loan->loan_no,$customer_no,$last_repayment->remaining_amount,$last_row_bank->balance,0);
                }
                $customer_update = DB::table('customer')->where('id','=',$customer_id)->update([
                    'status'=>"active"
                ]);
                $adjustment_payment = $this->adjustmentToRepayment($loan_id,$bank_book_id,$cash_book_id,$last_repayment->remaining_amount,$last_repayment->installment_count+1);
                $loan_update = $this->updateLoan($loan_id);
                if ($customer_update>0 && $adjustment_payment!=null && $loan_update>0) {
                    return response()->json([
                        'error'=>false,
                        'message'=>"Loan cleared",
                    ]);
                } else {
                    return response()->json([
                        'error'=>true,
                        'message'=>"Loan clear failed!"
                    ]);
                }
            } else {
                return response()->json([
                    'error'=>true,
                    'message'=>"Loan is already been cleared!"
                ]);
            }
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"Loan not found!"
            ]);
        }
    }

    private function adjustmentToRepayment($loan_id, $bank_book_id, $cash_book_id, $amount, $installment_count) {
        try {
            $repayment = new Customer_repayment();
            $repayment->loan_id = $loan_id;
            $repayment->bank_book_id = $bank_book_id;
            $repayment->cash_book_id = $cash_book_id;
            $repayment->amount = $amount;
            $repayment->installment_count = $installment_count;
            $repayment->remaining_amount = 0.00;
            $repayment->save();

            return $repayment->id;
        } catch(Exception $e){
            return null;
        }
    }

    private function updateLoan($loan_id) {
        $count = DB::table('customer_loan')
            ->where('id','=',$loan_id)
            ->update([
                'due_amount'=>0,
                'updated_at'=>Carbon::now()
            ]);

        return $count;
    }

    /**
     * @param $loan_no
     * @param $customer_no
     * @param $deposit_amount
     * @param $last_balance
     * @return mixed|null
     */
    private function addLoanClearCashBookEntry($loan_no,$customer_no,$deposit_amount,$last_balance) {
        try{
            $cash_clear_entry = new Cash_book();
            $cash_clear_entry->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $cash_clear_entry->description = "loan no ". $loan_no." cleared to customer no ".$customer_no;
            $cash_clear_entry->deposit = $deposit_amount;
            $cash_clear_entry->withdraw = 0;
            $cash_clear_entry->balance = $last_balance+$deposit_amount;
            $cash_clear_entry->save();

            return $cash_clear_entry->id;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $loan_no
     * @param $customer_no
     * @param $deposit_amount
     * @param $last_balance
     * @param $cheque_no
     * @return mixed|null
     */

    private function addLoanClearBankBookEntry($loan_no,$customer_no,$deposit_amount,$last_balance,$cheque_no) {
        try{
            $bank_clear_entry = new Bank_book();
            $bank_clear_entry->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $bank_clear_entry->description = "loan no ".$loan_no." cleared to customer no ".$customer_no;
            $bank_clear_entry->deposit = $deposit_amount;
            $bank_clear_entry->withdraw = 0;
            $bank_clear_entry->balance = $last_balance+$deposit_amount;
            $bank_clear_entry->cheque_no = $cheque_no;
            $bank_clear_entry->save();

            return $bank_clear_entry->id;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getLoanDetailsByNumber(Request $request) {
        $loan_number = $request['loan_no'];

        $loan_id = DB::table('customer_loan')->where('loan_no','=',$loan_number)
            ->select('id')
            ->pluck('id');

        $loan_details = DB::table('customer_loan_repayment')->where('loan_id','=',$loan_id)
            ->get();

        return response()->json([
            'error'=>false,
            'loan_details'=>$loan_details
        ]);
    }

    //check again
    public function getLoanList() {
        $loans=[];
        $routes = DB::table('route')->get();
        if (!empty($routes)) {
            foreach ($routes as $route) {
                $customerSet = $this->customerController->getCustomersByRouteId($route->id);
                foreach ($customerSet as $customer) {
                    $loanSet = $this->customerController->getLoansByCustomerId($customer);
                    $item = [
                        'route_id'=>$route->id,
                        'loans'=>$loanSet
                    ];
                    $loans[] = $item;
                }
            }
        }
        return response()->json([
            'error' => false,
            'loans' => $loans,
            'routes' => $routes
        ]);
    }
}
