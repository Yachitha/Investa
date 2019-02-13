<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class DailySummaryController extends Controller
{
    public function fetch ()
    {
        $today = Carbon::now ()->format ( 'Y-m-d' );
        try {
            $repayments = DB::table ( 'customer_loan_repayment' )->whereDate ( 'created_at' , $today )->get ();
            if (!$repayments->isEmpty ()) {
                $array = [];
                foreach ($repayments as $repayment) {
                    try {
                        $loan = DB::table ( 'customer_loan' )->where ( 'id' , $repayment->loan_id )->first();
                        if (!empty($loan)){
                            $customer = DB::table ('customer')->where ('id',$loan->customer_id)->first ();
                            if (!empty($customer)){
                                $route = DB::table ('route')->where ('id',$customer->route_id)->first();
                                if (!empty($route)) {
                                    $item = [
                                        'route'=>$route->name,
                                        'repayment'=>$repayment,
                                        'customer_no'=>$customer->customer_no
                                    ];
                                    $array []= $item;
                                }else {
                                    dd ("route not exists");
                                }
                            }else {
                                dd ("customer not exists");
                            }

                        }else{
                            dd ("loan is not present");
                        }
                    } catch (Exception $e) {
                        dd ( $e );
                    }
                }
            }else {
                dd ("no repayments in the table");
            }
        }catch (Exception $e) {
            dd($e);
        }
        if ( !empty( $array ) ) {
            $sorted = collect ( $array )->groupBy ( 'route' );
            //dd($sorted);
            return view ( 'daywork.dailySummary' , ['sorted' => $sorted] );
        } else {
            return view ( 'daywork.dailySummary' , ['sorted' => []] );
        }
    }
}
