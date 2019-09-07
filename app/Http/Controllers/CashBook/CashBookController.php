<?php


namespace App\Http\Controllers\CashBook;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashBookController extends Controller
{
    public function getCashBookRecords(Request $request) {
        $start_date = Carbon::parse($request['start_date']);
        $end_date = Carbon::parse($request['end_date']);

        if ($start_date->eq($end_date)) {
            return response()->json([
                'error'=>false,
                'records'=>DB::table('cash_book')->whereDate('created_at','=',$start_date)->whereNull('deleted_at')->get()
            ]);
        } elseif ($end_date->gt($start_date)) {
            return response()->json([
                'error'=>false,
                'records'=>DB::table('cash_book')->whereBetween('created_at',[$start_date,$end_date])->whereNull('deleted_at')->get()
            ]);
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"end date must be greater than start date!"
            ]);
        }
    }
}