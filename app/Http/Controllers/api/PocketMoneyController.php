<?php

namespace App\Http\Controllers\api;

use App\Cash_book;
use App\PocketMoney;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PocketMoneyController extends Controller
{
    /**
     * pocket money comes under salesRep expenses
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Fields=> id, transaction_date, description, amount, sales_rep_id, timestamps
    **/

    public function createPocketMoneyRow (Request $request)
    {
        if ( !empty( $request ) ) {
            $amount = $request['amount'];
            $description = $request['description'];
            $sales_rep_id = $request['id'];
            $transaction_date = Carbon::now ()->format ( 'Y-m-d H:i:s' );

            if ( !empty( $sales_rep_id ) ) {

                try{
                    $cash_book = new Cash_book();
                    $cash_book->transaction_date = $transaction_date;
                    $cash_book_description = DB::table ( 'users' )->where ( 'id' , $sales_rep_id )->select ('name')->pluck ('name')->first() .
                        " spends for " . $description;
                    $cash_book->description = $cash_book_description;
                    $cash_book->deposit = 0.0;
                    $cash_book->withdraw = $amount;
                    $last_bal_row = DB::table ( 'cash_book' )->orderBy ( 'id' , 'desc' )->first ();
                    $cash_book->balance = $last_bal_row? ($last_bal_row->balance - $amount) : (-$amount);
                    $cash_book->save ();
                }catch (\Exception $e) {
                    return response ()->json ([
                        'error'=>true,
                        'message'=>"Unable to put a record to cash book"
                    ]);
                }

                try{
                    $pocket_money = new PocketMoney();
                    $pocket_money->transaction_date = $transaction_date;
                    $pocket_money->amount = $amount;
                    $pocket_money->description = $description;
                    $pocket_money->sales_rep_id = $sales_rep_id;
                    $pocket_money->cash_book_id = $cash_book->id;
                    $pocket_money->bank_book_id = null;
                    $pocket_money->save ();
                    return response ()->json ( [
                        'error' => false ,
                        'entry' => $pocket_money
                    ] );
                }catch (\Exception $e) {
                    return response ()->json ([
                        'error'=>true,
                        'message'=>"Error while data inserting"
                    ]);
                }

            } else {
                return response ()->json ( [
                    'error' => true ,
                    'message' => "Sales rep id cannot be null"
                ] );
            }

        } else {
            return response ()->json ( [
                'error' => true ,
                'message' => "Invalid data"
            ] );
        }
    }
}
