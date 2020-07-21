<?php


namespace App\Http\Controllers\Reports\NewLoanReport;


use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewLoanReport
{
    public function getNewLoans (Request $request) {
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

                $new_loans = DB::table('customer_loan')
                    ->whereDate('created_at','=',$start_date)
                    ->whereNull('deleted_at')
                    ->get();

                return response()->json([
                    'error'=>false,
                    'new_loans'=>$this->attachCustomerDetailsToLoans($new_loans)
                ]);
            } elseif ($end_date->gt($start_date)) {

                $new_loans = DB::table('customer_loan')
                    ->whereBetween('created_at',[$start_date,$end_date])
                    ->whereNull('deleted_at')
                    ->get();

                return response()->json([
                    'error'=>false,
                    'new_loans'=>$this->attachCustomerDetailsToLoans($new_loans)
                ]);
            } else {
                return response()->json([
                    'error'=>true,
                    'message'=>"end date must be greater than start date!"
                ]);
            }
        }
    }

    private function attachCustomerDetailsToLoans($customer_loans) {
        foreach ($customer_loans as $customer_loan) {
            try{
                $customer = DB::table('customer')->where('id','=',$customer_loan->customer_id)
                    ->first();

                $customer_loan->name = $customer->name;
                $customer_loan->customer_no = $customer->customer_no;

                if (Carbon::now()->gt($customer_loan->end_date)) {
                    $customer_loan->is_exp = true;
                    $customer_loan->exp_dates = Carbon::now()->diffInDays($customer_loan->end_date);
                } else {
                    $customer_loan->is_exp = false;
                    $customer_loan->exp_dates = 0;
                }

            } catch (Exception $e) {
                return response()->json([
                    'error'=>false,
                    'message'=>$e->getMessage()
                ]);
            }
        }

        return $customer_loans;
    }
}
