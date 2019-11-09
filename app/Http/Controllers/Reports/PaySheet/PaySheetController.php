<?php


namespace App\Http\Controllers\Reports\PaySheet;


use App\Bank_book;
use App\BankAccountDetail;
use App\BankWithdraw;
use App\Cash_book;
use App\Http\Controllers\Reports\ReportController;
use App\SalesRepSalaryPayment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaySheetController extends ReportController
{
    public function getInitialData() {
        $sales_reps = DB::table('users')->where('role_id','=',2)->whereNull('deleted_at')->get();
        $bank_accounts = DB::table('bank_account_details')->get();

        return response()->json([
            'error'=>false,
            'sales_reps'=>$sales_reps,
            'bank_accounts'=>$bank_accounts
        ]);
    }

    public function getPayDataForSalesRep (Request $request) {
        $validator = Validator::make($request->all(),[
            'date'=>"required",
            'sales_rep_id'=>"required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>$validator->errors()
            ]);
        } else {
            $date = $request['date'];
            $sales_rep_id = $request['sales_rep_id'];
            $dateArr = explode('-',$date);
            $year = $dateArr[0];
            $month = $dateArr[1];

            $monthStartDate = Carbon::createFromDate($year,$month,1);
            $basic_salary = $this->getBasicSalary($sales_rep_id, $monthStartDate);
            $commissions_details = $this->loanCommissionDetails($sales_rep_id,$monthStartDate);
            $commissions_total = $commissions_details['total'];
            $commissions = $commissions_details['commissions'];
            $total_salary = round($basic_salary + $commissions_total, 2);
            $salary_pay_details = $this->getPayments($sales_rep_id, $monthStartDate);
            $payment_total = $salary_pay_details['total'];
            $salary_payments = $salary_pay_details['payments'];
            $total_final_salary = round($total_salary-$payment_total, 2);

            return response()->json([
                'error'=>false,
                'commissions'=>$commissions,
                'salary_payments'=>$salary_payments,
                'basic_salary'=>$basic_salary,
                'loan_commission'=>$commissions_total,
                'total'=>$total_salary,
                'payment'=>$payment_total,
                'total_salary'=>$total_final_salary
            ]);
        }
    }

    private function getBasicSalary ($sales_rep_id, $date) {

        $basic = DB::table('sales_rep_salaries')->where('sales_rep_id','=',$sales_rep_id)
            ->whereMonth('created_at',Carbon::parse($date)->month)
            ->whereNull('deleted_at')
            ->select('basic')
            ->pluck('basic')
            ->first();

        return $basic;
    }

    private function loanCommissionDetails($sales_rep_id, $date) {
        $commissions = DB::table('sales_rep_commission')->whereMonth('created_at',Carbon::parse($date)->month)
            ->where('user_id',$sales_rep_id)
            ->whereNull('deleted_at')
            ->get();
        $total = 0.0;
        if ($commissions) {
            foreach ($commissions as $commission) {
                $total += $commission->commission_amount;
                $commission->customer = DB::table('customer')->where('id','=',$commission->user_id)->first();
                $commission->loan = DB::table('customer_loan')->where('id','=',$commission->loan_id)->first();
            }
        }

        $result = [
            'total'=>$total,
            'commissions'=>$commissions
        ];

        return $result;
    }

    private function getPayments ($sales_rep_id, $date){
        $payments = DB::table('sales_rep_salary_payments')->where('sales_rep_id',$sales_rep_id)
            ->whereMonth('created_at',Carbon::parse($date)->month)
            ->whereNull('deleted_at')
            ->get();
        $total = 0.0;
        if ($payments) {
            foreach ($payments as $payment) {
                $total += $payment->amount;
            }
        }

        $result = [
            'total'=>$total,
            'payments'=>$payments
        ];

        return $result;
    }

    public function addSalaryPayment(Request $request) {
        $validator = Validator::make($request->all(),[
            'date'=>"required",
            'sales_rep_id'=>"required",
            'payment'=>"required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>$validator->errors()
            ]);
        } else {
            try{
                $sales_rep_id = $request['sales_rep_id'];
                $amount = $request['payment'];
                $date = $request['date'];
                $type_id = $request['type_id'];
                $cash_amount = $request['cash_amount'];
                $bank_acc_id = $request['bank_acc_id'];
                $cheque_no = $request['cheque_no'];
                $description = "Advance or Salary Payment";

                $bank_amount = $amount-$cash_amount;
                $cash_book_id = null;
                $bank_book_id = null;
                $sales_rep_name = DB::table('users')->where('id',$sales_rep_id)
                    ->select('name')
                    ->pluck('name')
                    ->first();
                $type="Cash";
                if($type_id==-1) {
                    $cash_book_id = $this->createCashBookRecord($cash_amount,$sales_rep_name);
                    $bank_book_id = $this->createBankBookRecord($bank_amount,$sales_rep_name,$cheque_no);
                    $this->updateBankAccountDetails($bank_acc_id,$bank_amount);
                    $this->addBankWithdrawRecord($bank_acc_id,$bank_amount,$cheque_no);
                    $type = "Both";
                } elseif ($type_id==1) {
                    $cash_book_id = $this->createCashBookRecord($cash_amount,$sales_rep_name);
                    $type = "Cash";
                } elseif ($type_id==2) {
                    $bank_book_id = $this->createBankBookRecord($bank_amount,$sales_rep_name,$cheque_no);
                    $this->updateBankAccountDetails($bank_acc_id,$bank_amount);
                    $this->addBankWithdrawRecord($bank_acc_id,$bank_amount,$cheque_no);
                    $type = "Bank";
                }

                $sales_rep_salary_payment = new SalesRepSalaryPayment();
                $sales_rep_salary_payment->sales_rep_id = $sales_rep_id;
                $sales_rep_salary_payment->amount = $amount;
                $sales_rep_salary_payment->date = $date;
                $sales_rep_salary_payment->description = $description;
                $sales_rep_salary_payment->type = $type;
                $sales_rep_salary_payment->cash_book_id = $cash_book_id;
                $sales_rep_salary_payment->bank_book_id = $bank_book_id;
                $sales_rep_salary_payment->save();

                $payment_details = $this->getPayments($sales_rep_id, $date);

                return response()->json([
                    'error'=>false,
                    'message'=>"Payment recorded successfully!",
                    'payments'=>$payment_details['payments'],
                    'payment'=>$payment_details['total']
                ]);

            }catch (Exception $e) {
                return response()->json([
                    'error'=>true,
                    'message'=>$e
                ]);
            }
        }
    }

    private function createBankBookRecord($bank_amount, $salesRep_name, $cheque_no) {
        try {
            $bank_withdraw = new Bank_book();
            $bank_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $bank_withdraw->description = "salary payment to: " . $salesRep_name;
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

    private function createCashBookRecord($cash_amount, $salesRep_name) {
        try {
            $cash_withdraw = new Cash_book();
            $cash_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $cash_withdraw->description = "salary payment to " . $salesRep_name;
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

    private function updateBankAccountDetails($bank_acc_id, $bank_amount) {

        $acc_details_by_id = DB::table('bank_account_details')->where('id',$bank_acc_id)
            ->first();

        $bank_acc_update = DB::table('bank_account_details')->where('id',$bank_acc_id)
            ->update([
                'balance'=>$acc_details_by_id->balance+$bank_amount,
                'total_cash_out'=>$acc_details_by_id->total_cash_out+$bank_amount
            ]);
        return $bank_acc_update > 0 ? true : false;
    }

    private function addBankWithdrawRecord($bank_acc_id, $bank_amount, $cheque_no) {

        $bank_with_des_id = DB::table('bank_withdraw_description')->where('description', '=', "Salary Payment")
            ->select('id')->pluck('id')->first();

        $bank_withdraw = new BankWithdraw();
        $bank_withdraw->acc_id = $bank_acc_id;
        $bank_withdraw->with_des = $bank_with_des_id;
        $bank_withdraw->type = "Cheque";
        $bank_withdraw->amount = $bank_amount;
        $bank_withdraw->cheque_no = $cheque_no;
        $bank_withdraw->save();

    }
}
