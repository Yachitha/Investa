<?php


namespace App\Http\Controllers\Reports\CustomerOutstandingReport;


use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerOutstandingReportController
{
    public function getInitialDataCOReport() {
        $routes = DB::table('route')->whereNull('deleted_at')->get();

        return response()->json([
            'error'=>false,
            'routes'=>$routes,
        ]);
    }

    public function getCOReportByRoute(Request $request) {
        $validator = Validator::make($request->all(), [
            'route_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>$validator->errors()
            ]);
        } else {
            $route_id = $request['route_id'];

            if ($route_id == -1){

                $customer_loans = DB::table('customer_loan')
                    ->where('due_amount','>',0.00)
                    ->whereNull('deleted_at')->get();

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
                return response()->json([
                    'error'=>false,
                    'outstanding_loans'=>$customer_loans
                ]);
            } else {

                $route_customer_loans = DB::table('customer_loan')
                    ->join('customer','customer.id','=','customer_loan.customer_id')
                    ->where('customer.route_id','=',$route_id)
                    ->where('customer_loan.due_amount','>',0.00)
                    ->whereNull('customer_loan.deleted_at')
                    ->get();

                foreach ($route_customer_loans as $customer_loan) {
                    if (Carbon::now()->gt($customer_loan->end_date)) {
                        $customer_loan->is_exp = true;
                        $customer_loan->exp_dates = Carbon::now()->diffInDays($customer_loan->end_date);
                    } else {
                        $customer_loan->is_exp = false;
                        $customer_loan->exp_dates = 0;
                    }
                }

                return response()->json([
                    'error'=>false,
                    'outstanding_loans'=>collect($route_customer_loans)
                ]);
            }
        }
    }
}
