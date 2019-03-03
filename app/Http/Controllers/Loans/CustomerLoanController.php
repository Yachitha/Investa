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
use App\SalesRepCommission;
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
                    ->orWhere('name', '=', $request['parameter']);

                if ($customer_id == null) {
                    return response()->json([
                        'error' => true,
                        'message' => "No such customer exists"
                    ]);

                } else {
                    $loans = DB::table('customer_loan')->where('customer_id', '=', $customer_id)->get();

                    return response()->json([
                        'error' => false,
                        'loans' => $loans,
                        'customer_id' => $customer_id
                    ]);
                }

            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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
    private function checkIsActive($customer_id)
    {
        try {
            $status = DB::table('customer')->where('id', '=', $customer_id)->select('status')->pluck('status')->first();

            if ($status == "active") {
                return $active = true;
            } elseif ($status == "deactive" || $status == "ongoing") {
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
                $cash_amount = $request['cash_amount'];
                $bank_amount = $request['bank_amount'];
                $loan_interest = $request['loan_interest'];
                $payment_days = $request['payment_days'];
                $customer_id = $request['customer_id'];
                $cheque_no = $request['cheque_no'];

                $customer_name = DB::table('customer')->where('id', '=', $customer_id)->select('name')->pluck('name')->first();
                $cash_withdraw_id = null;
                $bank_withdraw_id = null;
                if ($cash_amount != 0) {
                    $cash_withdraw_id = $this->addCashBookRecord($customer_name, $cash_amount);
                } else if ($bank_amount != 0) {
                    $cash_withdraw_id = $this->addBankBookRecord($customer_name, $bank_amount, $cheque_no);
                }

                $loan_amount = $this->calculateLoanAmount($cash_amount, $bank_amount);
                $total_loan_amount = $this->totalLoanAmount($cash_amount, $bank_amount, $duration, $loan_interest);
                $installment_amount = $this->calculateInstallmentAmount($total_loan_amount, $duration);

                $customer_loan_id = null;
                if ($cash_withdraw_id != null || $bank_withdraw_id != null) {
                    $customer_loan_id = $this->addLoan($loan_no, $loan_interest, $loan_amount, $total_loan_amount, $installment_amount, $payment_days, $start_date, $end_date, $duration, $customer_id, $cash_withdraw_id, $bank_withdraw_id);
                }

                $this->addInitialRepaymentColumn($customer_loan_id, $payment_days, $loan_amount);

                $customer = Customer::where('id', $request['customer_id'])->first();

                if ($customer) {
                    $customer->status = "ongoing";
                    $customer->save();
                }

                $this->addSalesRepCommission($commission_rate,$loan_amount,Carbon::now(),$customer_loan_id,$salesRep_id);

            } else {
                return response()->json([
                    'error' => true,
                    'message' => "This customer already have an ongoing loan"
                ]);
            }

            return response()->json([
                'customer_loan_id'=>$customer_loan_id,
                'loan_amount'=>$loan_amount,
                'total_loan_amount'=> $total_loan_amount,
                'installment_amount'=>$installment_amount,
            ]);
        }
    }

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

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

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
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function calculateLoanAmount($cashAmount, $bankAmount)
    {
        return $cashAmount + $bankAmount;
    }

    private function totalLoanAmount($cashAmount, $bankAmount, $duration, $rate)
    {
        return $this->calculateLoanAmount($cashAmount, $bankAmount) + ($cashAmount + $bankAmount) * ($rate / 30) * ($duration / 100);
    }

    private function calculateInstallmentAmount($totalLoan, $duration)
    {
        return $totalLoan / $duration;
    }

    private function addSalesRepCommission($commission_rate, $loan_amount, $date, $loan_id, $user_id) {
        try{
            $commission = new SalesRepCommission();
            $commission['commission_rate'] = $commission_rate;
            $commission['commission_amount'] = ($loan_amount*$commission_rate)/100;
            $commission['date'] = $date;
            $commission['loan_id'] = $loan_id;
            $commission['user_id'] = $user_id;

            $commission->save();

            return $commission->id;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function addInitialRepaymentColumn($customer_loan_id, $no_of_installments, $loan_amount) {
        try{
            $repayment = new Customer_repayment();
            $repayment->loan_id = $customer_loan_id;
            $repayment->bank_book_id = null;
            $repayment->cash_book_id = null;
            $repayment->amount = 0.0;
            $repayment->installment_count = $no_of_installments;
            $repayment->remaining_amount = $loan_amount;
            $repayment->save();

            return $repayment->id;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function addLoan($loan_no, $loan_interest, $loan_amount, $total_loan_amount, $installment_amount, $payment_days, $start_date, $end_date, $duration, $customer_id, $cash_withdraw_id, $bank_withdraw_id)
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

            $customer_loan->save();

            return $customer_loan->id;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * when start_date and duration given end_date of the loan will be calculate
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
}
