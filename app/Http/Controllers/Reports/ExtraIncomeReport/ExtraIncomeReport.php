<?php


namespace App\Http\Controllers\Reports\ExtraIncomeReport;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExtraIncomeReport
{
    public function getIncomeDetails(Request $request) {
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
                $extra_income = DB::table('extra_incomes')->whereDate('created_at','=',$start_date)
                    ->whereNull('deleted_at')
                    ->get();

                return response()->json([
                    'error'=>false,
                    'incomes'=>$extra_income
                ]);
            } elseif ($end_date->gt($start_date)) {

                $extra_income = DB::table('extra_incomes')->whereBetween('created_at',[$start_date,$end_date])
                    ->whereNull('deleted_at')
                    ->get();

                return response()->json([
                    'error'=>false,
                    'incomes'=>$extra_income
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
