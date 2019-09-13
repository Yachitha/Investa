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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class DaySheet extends ReportController
{
    public function createPdf ()
    {
        $pdf = app ('dompdf.wrapper')->loadView('Reports.DaySheet.daySheet');
        return $pdf->download ('daySheet.pdf');
    }

    public function getDaySheetData() {
        $array["daily_payments"] = $this->createFakeData();
        return response()->json([
                'error'=>false,
                'data'=>collect($array)
            ]);
    }

    private function createFakeData() {
        $array = [];
        for ($i=0; $i<500; $i++) {
            $item = [
                'id'=>$i+800,
                'name'=>"south contest".$i,
                'amount'=>"-"
            ];
            $array[] = $item;
        }
        $group = collect($array)->split(count($array)/53)->toArray();
        return $group;
    }

    public function getInitialData() {
        $routes = DB::table('route')->whereNull('deleted_at')->get();
        $sales_reps = DB::table('users')->where('role_id','=',2)->whereNull('deleted_at')->get();

        return response()->json([
            'error'=>false,
            'routes'=>$routes,
            'sales_reps'=>$sales_reps
        ]);
    }

    public function getDataByDate(Request $request) {

        $validator = Validator::make($request->all(), [
            'date'=>'required',
            'route_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>$validator->errors()
            ]);
        } else {
            $date = Carbon::parse($request['date']);
            $route_id = $request['route_id'];

            if ($route_id == -1){
                $bf_amount = $this->getBFAmount($date);
                $route_col = $this->getCollectionsByRoute($date);
                $income_details = $this->getIncomeDetails($date);
            }
        }
    }

    private function getBFAmount($date) {

        $record = DB::table('cash_book')->whereDate('created_by','=',Carbon::parse($date))->whereNull('deleted_at')->orderBy('id','asc')->first();

        if ($record) {
            return $record->balance;
        } else {
            return DB::table('cash_book')->whereNull('deleted_at')->latest('balance');
        }
    }

    private function getCollectionsByRoute($date) {
        $routes = DB::table('route')->whereNull('deleted_at')->get();
        $arr = [];
        foreach ($routes as $route) {
            $arr = [
                $route->name => $this->getCollection($route->id, $date)
            ];
        }

        return $arr;
    }

    private function getCollection($route_id, $date) {
        $customers = DB::table('customer')->where('route_id','=',$route_id)->whereNull('deleted_at')->get();
        $collection = 0;

        foreach ($customers as $customer) {
            $loans = DB::table('customer_loan')->where('customer_id','=',$customer->id)->whereNull('deleted_at')->get();
            if ($loans) {
                foreach ($loans as $loan) {
                    $amount = DB::table('customer_repayment')->where('loan_id','=',$loan->id)->whereDate('created_at','=',$date)->select('amount')->pluck('amount')->first();
                    $collection+=$amount;
                }
            }
        }

        return $collection;
    }

    private function getIncomeDetails($date) {
        $output = [];
        $total_collections = DB::table('customer_repayment')->whereDate('created_at','=',$date)->select('amount')->pluck('amount')->sum();
        $ex_income = 0;
        $sup_loan_in = DB::table('supplier_loan')->whereDate('created_at','=',$date)->whereNull('deleted_at')->select('loan_amount')->pluck('loan_amount')->sum();


        $output = [
            "total_col"=>$total_collections,
        ];

        return $output;
    }

    private function getExpenseDetails() {

    }
}
