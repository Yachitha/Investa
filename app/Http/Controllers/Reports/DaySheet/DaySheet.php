<?php
/**
 * Created by PhpStorm.
 * User: Yachitha Sandaruwan
 * Date: 11/22/2018
 * Time: 10:19 PM
 */

namespace App\Http\Controllers\Reports\DaySheet;


use function app;
use App\Http\Controllers\Reports\ReportController;
use App\Locker;
use App\RoutesBF;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use JasperPHP\JasperPHP;


class DaySheet extends ReportController
{
    public function createPdf()
    {
        $pdf = app('dompdf.wrapper')->loadView('Reports.DaySheet.daySheet');
        return $pdf->download('daySheet.pdf');
    }

    public function getDaySheetData()
    {
        $array["daily_payments"] = $this->createFakeData();
        return response()->json([
            'error' => false,
            'data' => collect($array)
        ]);
    }

    private function createFakeData()
    {
        $array = [];
        for ($i = 0; $i < 500; $i++) {
            $item = [
                'id' => $i + 800,
                'name' => "south contest" . $i,
                'amount' => "-"
            ];
            $array[] = $item;
        }
        $group = collect($array)->split(count($array) / 53)->toArray();
        return $group;
    }

    // create day sheet pdf using dompdf
    public function daySheetPdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'route_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ]);
        } else {
            $date = Carbon::parse($request['date']);
            $route_id = $request['route_id'];

            if ($route_id != -1) {
                $route_name = DB::table('route')->where('id', '=', $route_id)
                    ->select('name')->pluck('name')->first();

                $sales_rep_name = DB::table('users')->where('route_id', '=', $route_id)
                    ->where('role_id', '=', 2)->select('name')->pluck('name')
                    ->first();


                $header_details = [
                    'route_name' => $route_name,
                    'sales_rep_name' => $sales_rep_name,
                    'date' => $date,
                    'due_total' => 10001
                ];
            }
        }
    }

    private function getDueTotalForRoute($route_id)
    {

    }

    private function getPaymentDataForDate()
    {

    }

    public function getInitialData()
    {
        $routes = DB::table('route')->whereNull('deleted_at')->get();
        $sales_reps = DB::table('users')->where('role_id', '=', 2)->whereNull('deleted_at')->get();

        return response()->json([
            'error' => false,
            'routes' => $routes,
            'sales_reps' => $sales_reps
        ]);
    }

    public function getDataByDate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'route_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ]);
        } else {
            $date = Carbon::parse($request['date']);
            $route_id = $request['route_id'];

            if ($route_id == -1) {
                $bf_amount = $this->getBFAmount($date);
                $route_col = $this->getCollectionsByRoute($date);
                $income_details = $this->getIncomeDetails($date);
                $expense_details = $this->getExpenseDetails($date);
                $bank_deposit = $this->getBankDeposit($date);
                $total_income = $income_details['income'] - $expense_details['expenses'];
                $day_cash = $total_income - $bank_deposit;
                $day_profit = $this->calculateDayProfit($date);

                return response()->json([
                    'error' => false,
                    'bf_amount' => $bf_amount,
                    'route_col' => $route_col,
                    'income_details' => $income_details,
                    'expense_details' => $expense_details,
                    'total_income' => $total_income,
                    'bank_deposit' => $bank_deposit,
                    'day_cash' => $day_cash,
                    'day_profit' => $day_profit
                ]);
            } else {

                $bf_amount = $this->getBfAmountForRoute($date, $route_id);
                $route_col = $this->getCollectionsByRoute($date);
                $income_details = $this->getIncomeDetailsForRoute($date, $route_id);
                $expense_details = $this->getExpenseDetailsForRoute($date, $route_id);
                $total_income = $income_details['income'] - $expense_details['expenses'];
                $bank_deposit = 0.0;
                $day_cash = 0.0;
                $day_profit = $this->getDayProfitByRoute($date, $route_id);

                return response()->json([
                    'error' => false,
                    'bf_amount' => $bf_amount,
                    'route_col' => $route_col,
                    'income_details' => $income_details,
                    'expense_details' => $expense_details,
                    'total_income' => $total_income,
                    'bank_deposit' => $bank_deposit,
                    'day_cash' => $day_cash,
                    'day_profit' => $day_profit
                ]);
            }
        }
    }

    private function getBFAmount($date)
    {
        $bf_amount = 0.0;

        $bfs = DB::table('routes_b_fs')->whereDate('created_at', '=', Carbon::parse($date)->subDay())->get();

        if ($bfs) {
            foreach ($bfs as $bf) {
                $bf_amount += $bf->amount;
            }
        }

        return $bf_amount;
    }

    private function getCollectionsByRoute($date)
    {
        $routes = DB::table('route')->whereNull('deleted_at')->get();
        $arr1 = [];
        foreach ($routes as $route) {
            $re_array = $this->getCollection($route->id, $date);
            $arr = [
                'name' => $route->name,
                'amount' => $re_array['collection_amount'],
                'due_total' => $re_array['due_total']
            ];
            $arr1[] = $arr;
        }

        return $arr1;
    }

    private function getCollection($route_id, $date)
    {
        $customers = DB::table('customer')->where('route_id', '=', $route_id)->whereNull('deleted_at')->get();
        $collection = 0;
        $due_total = 0;

        foreach ($customers as $customer) {
            $loans = DB::table('customer_loan')->where('customer_id', '=', $customer->id)->whereNull('deleted_at')->get();
            if ($loans) {
                foreach ($loans as $loan) {
                    $due_total += $loan->installment_amount;
                    $amount = DB::table('customer_loan_repayment')->where('loan_id', '=', $loan->id)->whereDate('created_at', '=', $date)->select('amount')->pluck('amount')->first();
                    $collection += $amount;
                }
            }
        }

        $result = [
            'due_total' => round($due_total, 2),
            'collection_amount' => $collection
        ];

        return $result;
    }

    private function getIncomeDetails($date)
    {
        $total_collections = DB::table('customer_loan_repayment')->whereDate('created_at', '=', $date)->select('amount')->pluck('amount')->sum();
        $ex_income = 0;
        $sup_loan_details = $this->getSupplierLoan($date);
        $emp_loan_pay = $this->getTotalEmpLoanPay($date);
        $sup_loan_total = $sup_loan_details['sup_loan_cash'] + $sup_loan_details['sup_loan_che'];

        $output = [
            "total_col" => $total_collections,
            "ex_income" => $ex_income,
            "sup_loan_in" => $sup_loan_details,
            "emp_loan_pay" => $emp_loan_pay,
            "income" => $total_collections + $ex_income + $sup_loan_total
        ];

        return $output;
    }

    private function getSupplierLoan($date)
    {
        $loans = DB::table('supplier_loan')->whereDate('created_at', '=', $date)->get();

        $cash_total = 0;
        $cheque_total = 0;

        if ($loans) {
            foreach ($loans as $loan) {
                if ($loan->type == "Both") {
                    $cash_total += DB::table('cash_book')->where('id', '=', $loan->cash_book_id)->select('deposit')->pluck('deposit')->first();
                    $cheque_total += DB::table('bank_book')->where('id', '=', $loan->bank_book_id)->select('deposit')->pluck('deposit')->first();
                } elseif ($loan->type == "Cash") {
                    $cash_total += $loan->amount;
                } else {
                    $cheque_total += $loan->amount;
                }
            }
        }
        $result = [
            'sup_loan_cash' => $cash_total,
            'sup_loan_che' => $cheque_total
        ];

        return $result;
    }

    private function getTotalEmpLoanPay($date)
    {
        $payments = DB::table('sales_rep_loan_repayment')->whereDate('created_at', '=', $date)->get();

        $total = 0.0;

        if ($payments) {
            foreach ($payments as $payment) {
                $total += $payment->amount;
            }
        }

        return $total;
    }

    private function getExpenseDetails($date)
    {
        $loan_total = $this->getTotalLoans($date);
        $loan_total_cash = $loan_total['total_cash'];
        $loan_total_che = $loan_total['total_che'];
        $salary_payment = $this->getTotalSalaryPay($date);
        $total_supplier_payment = $this->getSupTotalPayments($date);
        $total_supplier_payment_cash = $total_supplier_payment['total_cash'];
        $total_supplier_payment_cheque = $total_supplier_payment['total_bank'];
        $total_emp_loan = $this->getTotalEmpLoans($date);
        $extra_expenses = $this->getExtraExpenses($date);
        $expenses = $loan_total_cash + $loan_total_che + $salary_payment + $total_supplier_payment_cash + $total_supplier_payment_cheque + $total_emp_loan + $extra_expenses;


        $expense_details = [
            'loan_total_cash' => $loan_total_cash,
            'loan_total_che' => $loan_total_che,
            'salary_payment' => $salary_payment,
            'total_supplier_payment_cash' => $total_supplier_payment_cash,
            'total_supplier_payment_cheque' => $total_supplier_payment_cheque,
            'total_emp_loan' => $total_emp_loan,
            'extra_expenses' => $extra_expenses,
            'expenses' => $expenses
        ];

        return $expense_details;
    }

    private function getTotalLoans($date)
    {
        $loans = DB::table('customer_loan')->whereDate('created_at', '=', $date)->get();

        $total_cash = 0.0;
        $total_che = 0.0;
        if ($loans) {
            foreach ($loans as $loan) {
                if ($loan->type == "Both") {
                    $total_cash += DB::table('cash_book')->where('id', '=', $loan->cash_book_id)->select('withdraw')->pluck('withdraw')->first();
                    $total_che += DB::table('bank_book')->where('id', '=', $loan->bank_book_id)->select('withdraw')->pluck('withdraw')->first();
                } elseif ($loan->type == "Cash") {
                    $total_cash += $loan->loan_amount;
                } else {
                    $total_che += $loan->loan_amount;
                }
            }
        }

        $total = [
            'total_cash' => $total_cash,
            'total_che' => $total_che
        ];

        return $total;
    }

    private function getTotalSalaryPay($date)
    {
        $salaries = DB::table('sales_rep_salary_payments')->whereDate('created_at', '=', $date)->get();

        $total = 0.0;
        if ($salaries) {
            foreach ($salaries as $salary) {
                $total += $salary->amount;
            }
        }

        return $total;
    }

    private function getSupTotalPayments($date)
    {
        $payments = DB::table('supplier_loan_repayment')->whereDate('updated_at', '=', $date)->get();

        $total_cash = 0.0;
        $total_bank = 0.0;
        if ($payments) {
            foreach ($payments as $payment) {
                if ($payment->type == "Both") {
                    $total_cash += DB::table('cash_book')->where('id', '=', $payment->cash_book_id)->select('deposit')->pluck('deposit')->first();
                    $total_bank += DB::table('bank_book')->where('id', '=', $payment->bank_book_id)->select('deposit')->pluck('deposit')->first();
                } elseif ($payment->type == "Cash") {
                    $total_cash += $payment->amount;
                } else {
                    $total_bank += $payment->amount;
                }
            }
        }

        $total = [
            'total_cash' => $total_cash,
            'total_bank' => $total_bank
        ];

        return $total;
    }

    private function getTotalEmpLoans($date)
    {
        $loans = DB::table('sales_rep_loan')->whereDate('updated_at', '=', $date)->get();

        $total = 0.0;
        if ($loans) {
            foreach ($loans as $loan) {
                $total += $loan->loan_amount;
            }
        }

        return $total;
    }

    private function getExtraExpenses($date)
    {
        $ex_exp = DB::table('other_expenses')->whereDate('created_at', '=', $date)->get();

        $total = 0.0;

        if ($ex_exp) {
            foreach ($ex_exp as $exp) {
                $total += $exp->amount;
            }
        }

        $other_exp = DB::table('other_expenses')->whereDate('created_at', '=', $date)->get();

        if ($other_exp) {
            foreach ($other_exp as $item) {
                $total += $item->amount;
            }
        }

        return $total;
    }

    private function getBankDeposit($date)
    {
        $bank_deposits = DB::table('bank_deposits')->whereDate('created_at', '=', $date)->get();

        $total = 0.0;
        if ($bank_deposits) {
            foreach ($bank_deposits as $bank_deposit) {
                $total += $bank_deposit->amount;
            }
        }

        return $total;
    }

    private function calculateDayProfit($date)
    {
        $repayments = DB::table('customer_loan_repayment')->whereDate('created_at', '=', $date)->get();

        $profit = 0.0;
        foreach ($repayments as $repayment) {
            $loan = DB::table('customer_loan')->where('id', '=', $repayment->loan_id)->first();
            $profit += ($loan->total_loan_amount - $loan->loan_amount) / $loan->duration;
        }

        $expenses = 0.0;

        $sales_reps_ex = DB::table('sales_rep_expenses')->whereDate('created_at', '=', $date)->get();
        foreach ($sales_reps_ex as $ex) {
            $expenses += $ex->amount;
        }

        $other_ex = DB::table('other_expenses')->whereDate('created_at', '=', $date)->get();

        foreach ($other_ex as $other) {
            $expenses += $other->amount;
        }

        return round($profit - $expenses, 2);
    }

    private function getBfAmountForRoute($date, $route_id)
    {
        $amount = DB::table('routes_b_fs')->whereDate('created_at', '=', Carbon::parse($date)
            ->subDay())->where('route_id', '=', $route_id)->select('amount')
            ->pluck('amount')->first();

        if ($amount) {
            return $amount;
        } else {
            return 0;
        }
    }

    private function getIncomeDetailsForRoute($date, $route_id)
    {
        $total_collections = $this->getCollection($route_id, $date);
        $ex_income = 0.0;
        $sup_loan_details = $this->getSupplierLoan($date);
//        $emp_loan_pay = $this->getEmpLoanPayByRoute($date,$route_id);
        $emp_loan_pay = 0.0;
        $sup_loan_total = $sup_loan_details['sup_loan_cash'] + $sup_loan_details['sup_loan_che'];

        $output = [
            "total_col" => $total_collections['collection_amount'],
            "ex_income" => $ex_income,
            "sup_loan_in" => $sup_loan_details,
            "emp_loan_pay" => $emp_loan_pay,
            "income" => $total_collections['collection_amount'] + $ex_income + $sup_loan_total
        ];

        return $output;
    }

    private function getEmpLoanPayByRoute($date, $route_id)
    {
        $sales_rep_id = DB::table('users')->where('route_id', '=', $route_id)->whereNull('deleted_at')->select('id')->pluck('id')->first();
        $loan_id = DB::table('sales_rep_loan')->where('sales_rep_id', '=', $sales_rep_id)->whereNull('deleted_at')->select('id')->pluck('id')->first();

        $amount = DB::table('sales_rep_loan_repayment')->where('loan_id', '=', $loan_id)->whereDate('created_at', '=', $date)->select('amount')->pluck('amount')->first();

        return $amount;
    }

    private function getExpenseDetailsForRoute($date, $route_id)
    {
        $loan_details = $this->getLoanDetailsForRoute($date, $route_id);
        $loan_total_cash = $loan_details['total_cash'];
        $loan_total_che = $loan_details['total_che'];
        $salary_payment = 0.0;
        $total_supplier_payment_cash = 0.0;
        $total_supplier_payment_cheque = 0.0;
        $total_emp_loan = 0.0;
        $extra_expenses = 0.0;
        $expenses = $loan_total_cash + $loan_total_che + $salary_payment + $total_supplier_payment_cash + $total_supplier_payment_cheque + $total_emp_loan + $extra_expenses;

        $expense_details = [
            'loan_total_cash' => $loan_total_cash,
            'loan_total_che' => $loan_total_che,
            'salary_payment' => $salary_payment,
            'total_supplier_payment_cash' => $total_supplier_payment_cash,
            'total_supplier_payment_cheque' => $total_supplier_payment_cheque,
            'total_emp_loan' => $total_emp_loan,
            'extra_expenses' => $extra_expenses,
            'expenses' => $expenses
        ];

        return $expense_details;
    }

    private function getLoanDetailsForRoute($date, $route_id)
    {
        $sales_rep_id = DB::table('users')->where('route_id', '=', $route_id)
            ->whereNull('deleted_at')
            ->select('id')
            ->pluck('id')
            ->first();

        $loan_cash = 0.0;
        $loan_che = 0.0;

        if ($sales_rep_id) {
            $commissions = DB::table('sales_rep_commission')->whereDate('created_at', '=', $date)
                ->where('user_id', '=', $sales_rep_id)
                ->whereNull('deleted_at')
                ->get();
            if ($commissions) {
                foreach ($commissions as $commission) {
                    $loan = DB::table('customer_loan')->where('id', '=', $commission->loan_id)
                        ->whereDate('created_at', '=', $date)
                        ->whereNull('deleted_at')
                        ->first();

                    if ($loan) {
                        if ($loan->type == "Both") {
                            $loan_cash += DB::table('cash_book')->where('id', '=', $loan->cash_book_id)->select('deposit')->pluck('deposit')->first();
                            $loan_che += DB::table('bank_book')->where('id', '=', $loan->bank_book_id)->select('deposit')->pluck('deposit')->first();
                        } elseif ($loan->type == "Cash") {
                            $loan_cash += $loan->loan_amount;
                        } else {
                            $loan_che += $loan->loan_amount;
                        }
                    }
                }
            }
        }

        $loan_details = [
            'total_cash' => $loan_cash,
            'total_che' => $loan_che
        ];

        return $loan_details;
    }

    private function getDayProfitByRoute($date, $route_id)
    {
        $sales_rep_id = DB::table('users')
            ->where('route_id', '=', $route_id)
            ->whereNull('deleted_at')
            ->select('id')
            ->pluck('id')
            ->first();

        $comms = DB::table('sales_rep_commission')
            ->where('user_id', '=', $sales_rep_id)
            ->whereNull('deleted_at')
            ->get();

        $profit = 0.0;

        if ($comms) {
            foreach ($comms as $comm) {
                $loan = DB::table('customer_loan')
                    ->where('id', '=', $comm->loan_id)
                    ->whereNull('deleted_at')
                    ->first();
                if (DB::table('customer_loan_repayment')->where('customer_id', '=', $loan->id)->whereDate('created_at', '=', $date)->exists()) {
                    $profit += ($loan->total_loan_amount - $loan->loan_amount) / $loan->duration;
                }
            }
        }

        return $profit;
    }

    public function storeDaySheetData(Request $request)
    {
        $date = $request['date'];
        $route_id = $request['route_id'];
        $bfAmount = $request['bfAmount'];

        //income data
        $totalCol = $request['totalCol'];
        $exIncome = $request['exIncome'];
        $supLoanCash = $request['supLoanCash'];
        $supLoanChe = $request['supLoanChe'];
        $empLoanPay = $request['empLoanPay'];
        $dayAccess = $request['dayAccess'];
        $dayIncome = $request['dayIncome'];

        //expense data(cash out)
        $loanCash = $request['loanCash'];
        $loanChe = $request['loanChe'];
        $daySalaryPay = $request['daySalaryPay'];
        $daySupPayCash = $request['daySupPayCash'];
        $daySupPayChe = $request['daySupPayChe'];
        $empLoan = $request['empLoan'];
        $extraEx = $request['extraEx'];
        $expenses = $request['expenses'];

        //balancing data
        $dayTotIncome = $request['dayTotIncome'];
        $dayBDeposit = $request['dayBDeposit'];
        $dayCash = $request['dayCash'];
        $dayCashOut = $request['dayCashOut'];
        $dayBalance = $request['dayBalance'];
        $dayCashInLocker = $request['dayCashInLocker'];
        $dayProfit = $request['dayProfit'];

        $exists = DB::table('day_sheets')->whereDate('date', '=', $date)->whereNull('deleted_at')->exists();

        if (!$exists) {
            $daySheet = new \App\DaySheet();
            $daySheet->date = Carbon::parse($date)->format("Y-m-d H:i:s");
            $daySheet->bf_amount = $bfAmount;
            $daySheet->route_id = $route_id;
            $daySheet->loan_payment = $totalCol;
            $daySheet->e_income = $exIncome;
            $daySheet->sup_cash_loan = $supLoanCash;
            $daySheet->sup_che_loan = $supLoanChe;
            $daySheet->emp_loan_payment = $empLoanPay;
            $daySheet->access = $dayAccess;
            $daySheet->income = $dayIncome;
            $daySheet->loan_cash = $loanCash;
            $daySheet->loan_che = $loanChe;
            $daySheet->salary_payment = $daySalaryPay;
            $daySheet->sup_loan_cash_pay = $daySupPayCash;
            $daySheet->sup_loan_che_pay = $daySupPayChe;
            $daySheet->emp_loan = $empLoan;
            $daySheet->ex_expenses = $extraEx;
            $daySheet->expenses = $expenses;
            $daySheet->total_income = $dayTotIncome;
            $daySheet->bank_deposit = $dayBDeposit;
            $daySheet->day_cash = $dayCash;
            $daySheet->cash_out = $dayCashOut;
            $daySheet->balance = $dayBalance;
            $daySheet->cash_in_locker = $dayCashInLocker;
            $daySheet->day_profit = $dayProfit;
            $daySheet->save();

            $locker = new Locker();
            $locker->date = $date;
            $locker->amount = $dayBalance;
            $locker->cash_out = $dayCashOut;
            $locker->extra_str1 = null;
            $locker->extra_int1 = 0;
            $locker->extra_float1 = 0.0;
            $locker->save();

            $route_bf = new RoutesBF();
            $route_bf->route_id = $route_id;
            $route_bf->date = $date;
            $route_bf->amount = $dayBalance;
            $route_bf->acc = $dayAccess;
            $route_bf->user_id = Auth::id();
            $route_bf->save();

            return response()->json([
                'error' => false,
                'message' => "recorded successfully",
                'update' => false,
            ]);

        } else {
            return response()->json([
                'error' => true,
                'message' => "Day Sheet data have already been recorded",
                'update' => true
            ]);
        }

    }

    public function daySheetPrintDetails(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'date' => 'required',
//            'route_id' => 'required',
//            'bfAmount' => 'required',
//            'totalCol' => 'required',
//            'dayAccess' => 'required',
//            'loanCash' => 'required',
//            'loanChe' => 'required',
//            'extraEx' => 'required',
//            'dayBalance' => 'required'
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'error' => true,
//                'message' => $validator->errors()
//            ]);
//        } else {
//            $date = Carbon::parse($request['date']);
//            $bf_amount = $request['bfAmount'];
//            $total_col = $request['totalCol'];
//            $access = $request['dayAccess'];
//            $loan_cash = $request['loanCash'];
//            $loan_che = $request['loanChe'];
//            $extra_ex = $request['extraEx'];
//            $balance = $request['dayBalance'];
//            $route_id = $request['route_id'];
//
//            //not all routes
//            if ($route_id != -1) {
//                $route_name = DB::table('route')->where('id', '=', $route_id)
//                    ->select('name')->pluck('name')->first();
//
//                $sales_rep = DB::table('users')->where('route_id', '=', $route_id)
//                    ->where('role_id', '=', 2)
//                    ->whereNull('deleted_at')
//                    ->select('name')
//                    ->pluck('name')
//                    ->first();
//
//                $due_total = DB::table('customer_loan')
//                    ->join('customer', 'customer.id', '=', 'customer_loan.customer_id')
//                    ->where('customer.route_id', '=', $route_id)
//                    ->where('customer_loan.isFinished', '=', false)
//                    ->whereNull('customer_loan.deleted_at')
//                    ->sum('customer_loan.installment_amount');
//
//                $route_loans = DB::table('customer_loan')
//                    ->join('customer', 'customer.id', '=', 'customer_loan.customer_id')
//                    ->where('customer.route_id', '=', $route_id)
//                    ->where('customer_loan.isFinished', '=', false)
//                    ->whereNull('customer_loan.deleted_at')
//                    ->get();
//
//                $loan_repayments = $this->getLoanRepaymentList($route_loans, $date);
//
//            } else {
//                //all routes
//                $route_name = "ALL";
//                $sales_rep = "";
//
//                $due_total = DB::table('customer_loan')
//                    ->join('customer', 'customer.id', '=', 'customer_loan.customer_id')
//                    ->where('customer_loan.isFinished', '=', false)
//                    ->whereNull('customer_loan.deleted_at')
//                    ->sum('customer_loan.installment_amount');
//
//                $all_loans = DB::table('customer_loan')
//                    ->join('customer', 'customer.id', '=', 'customer_loan.customer_id')
//                    ->where('customer_loan.isFinished', '=', false)
//                    ->whereNull('customer_loan.deleted_at')
//                    ->get();
//
//                $loan_repayments = $this->getLoanRepaymentList($all_loans, $date);
//
//
//            }
//            set_time_limit(300);
//            $pdf = PDF::loadView('Reports.DaySheet.daySheet',compact(
//                ['date','bf_amount','total_col','access','loan_cash','loan_che','extra_ex','balance',
//                    'route_name','sales_rep','due_total','loan_repayments']
//            ));
//
//            return $pdf->download('day_sheet.pdf');
//        }
        $path = $this->saveDaySheetJson();

        if($path!=null) {
            return response()->json([
                'error'=>false,
                'path'=>$path
            ]);
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"File not created"
            ]);
        }
    }

    private function getLoanRepaymentList($loans, $date) {
        $list = [];

        foreach ($loans as $loan) {
            $repay = DB::table('customer_loan_repayment')->where('loan_id','=',$loan->id)
                ->whereDate('created_at','=',$date)
                ->whereNull('deleted_at')
                ->first();

            if ($repay) {
                $item = [
                    'loan_no'=>$loan->loan_no,
                    'customer_name'=>$loan->name,
                    'payment'=>$repay->amount
                ];
            } else {
                $item = [
                    'loan_no'=>$loan->loan_no,
                    'customer_name'=>$loan->name,
                    'payment'=>0.00
                ];
            }

            $list[] = $item;
        }

        return $list;
    }

    private function saveDaySheetJson() {
        $datafile = public_path() . '/day_sheet_json/day_sheet_1.json';
        $output = public_path() . '/temp/day_sheet'.time();
        $input = public_path() . '/jasper-template/day_sheet_test_json2.jrxml';

        try {
            $day_sheet_jp = new JasperPHP;
            $day_sheet_jp->process(
                $input,
                $output,
                array("pdf"),
                array(),
                array("driver"=>"json", "json_query"=>"result" ,"data_file"=>$datafile)
            )->execute();

            if(file_exists($output.".pdf")) {
                return $output.".pdf";
            } else {
                return null;
            }

        } catch (Exception $e) {
            Log::debug($e->getTraceAsString());
            return $e->getMessage();
        }
    }
}
