<?php

namespace App\Http\Controllers\Reports\MonthlySheet;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MonthlySheetController extends Controller
{
    public function getMonthlySheetData(Request $request){
        $validator = Validator::make($request->all(),[
            'date'=>"required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>$validator->errors()
            ]);
        } else {
            $date = $request['date'];
            $dateArr = explode('-',$date);
            $year = $dateArr[0];
            $month = $dateArr[1];

            $monthStartDate = Carbon::createFromDate($year,$month,1);


            $sheets = $this->getDaySummariesForMonth($monthStartDate);
            $summaries=$this->getSummariesForSheetsData($sheets);

            return response()->json([
                'error'=>false,
                'sheets'=>$sheets,
                'summaries'=>$summaries
            ]);
        }
    }

    private function getDaySummariesForMonth($date) {
        $sheets = DB::table('day_sheets')
            ->whereMonth('created_at','=',Carbon::parse($date)->month)
            ->where('route_id','!=',-1)
            ->whereNull('deleted_at')
            ->get();

        return $sheets;
    }

    private function getSummariesForSheetsData($sheets){

        $loan_payment = 0;
        $sup_cash_loan = 0;
        $sup_che_loan = 0;
        $extra_income = 0;
        $income = 0;
        $loan_cash = 0;
        $loan_che = 0;
        $salary_payment = 0;
        $sup_cash_pay = 0;
        $sup_che_pay = 0;
        $extra_expenses = 0;
        $expenses = 0;
        $monthly_income = 0;
        $monthly_deposit = 0;
        $monthly_profit = 0;

        foreach ($sheets as $sheet) {
            $loan_payment+=$sheet->loan_payment;
            $sup_cash_loan+=$sheet->sup_cash_loan;
            $sup_che_loan+=$sheet->sup_che_loan;
            $extra_income+=$sheet->e_income;
            $income+=$sheet->income;
            $loan_cash+=$sheet->loan_cash;
            $loan_che+=$sheet->loan_che;
            $salary_payment+=$sheet->salary_payment;
            $sup_cash_pay+=$sheet->sup_loan_cash_pay;
            $sup_che_pay+=$sheet->sup_loan_che_pay;
            $extra_expenses+=$sheet->ex_expenses;
            $expenses += $sheet->expenses;
            $monthly_income += $sheet->total_income;
            $monthly_deposit+=$sheet->bank_deposit;
            $monthly_profit+=$sheet->day_profit;
        }

        $summaries=[
            'loan_payment'=>$loan_payment,
            'sup_cash_loan' =>$sup_cash_loan,
            'sup_che_loan'=>$sup_che_loan,
            'extra_income'=>$extra_income,
            'income'=>$income,
            'loan_cash'=>$loan_cash,
            'loan_che'=>$loan_che,
            'salary_payment'=>$salary_payment,
            'sup_cash_pay'=>$sup_cash_pay,
            'sup_che_pay'=>$sup_che_pay,
            'extra_expenses'=>$extra_expenses,
            'expenses'=>$expenses,
            'monthly_income'=>$monthly_income,
            'monthly_deposit'=>$monthly_deposit,
            'monthly_profit'=>$monthly_profit,
        ];

        return $summaries;
    }
}
