<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashBookController extends Controller
{
    protected function fetchData(Request $request) {
        try{
            $month = $request['month'];
            if (empty($month)) {
                $month = Carbon::now ()->month;
                $cash_book_records = DB::table ('cash_book')->whereMonth ('created_at', $month)->get ();
                return $cash_book_records;
            } else {
                $formatted =  Carbon::parse ($month)->month;
                $cash_book_records = DB::table ('cash_book')->whereMonth ('created_at',$formatted)->get ();
                return $cash_book_records;
            }
        } catch (\Exception $e){
            return null;
        }
    }

    public function getCashBook(Request $request) {
        $data = $this->fetchData ($request);
        if ($data!=null) {
            return response ()->json (['error'=>false, 'data'=>$data]);
        } else{
            return response ()->json (['error'=>true, 'message'=>"error occurred!"]);
        }
    }
}
