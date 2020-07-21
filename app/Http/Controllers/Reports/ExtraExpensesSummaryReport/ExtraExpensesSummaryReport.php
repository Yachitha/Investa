<?php


namespace App\Http\Controllers\Reports\ExtraExpensesSummaryReport;


use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExtraExpensesSummaryReport
{
    public function getExpensesDetails(Request $request) {
        $validator = Validator::make($request->all(), [
            'start_date'=>'required',
            'end_date'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>$validator->errors()
            ]);
        } else {
            $start_date = Carbon::parse($request['start_date']);
            $end_date = Carbon::parse($request['end_date']);

            if ($start_date->eq($end_date)) {
                $other_exps = DB::table('other_expenses')->whereDate('created_at','=',$start_date)
                    ->whereNull('deleted_at')
                    ->get();

                $sales_rep_exps = DB::table('sales_rep_expenses')->whereDate('created_at','=',$start_date)
                    ->whereNull('deleted_at')
                    ->get();


                return response()->json([
                    'error'=>false,
                    'expenses'=>$sales_rep_exps->merge($other_exps)
                ]);
            } elseif ($end_date->gt($start_date)) {

                $other_exps = DB::table('other_expenses')->whereBetween('created_at',[$start_date,$end_date])
                    ->whereNull('deleted_at')
                    ->get();

                $sales_rep_exps = DB::table('sales_rep_expenses')->whereBetween('created_at',[$start_date,$end_date])
                    ->whereNull('deleted_at')
                    ->get();

                return response()->json([
                    'error'=>false,
                    'expenses'=>$sales_rep_exps->merge($other_exps)
                ]);
            } else {
                return response()->json([
                    'error'=>true,
                    'message'=>"end date must be greater than start date!"
                ]);
            }
        }
    }
}
