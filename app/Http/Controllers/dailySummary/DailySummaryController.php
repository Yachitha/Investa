<?php


namespace App\Http\Controllers\dailySummary;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailySummaryController extends Controller
{
    public function getInitialData() {
        $new_loans = DB::table('customer_loan')->whereDate('created_at','=',Carbon::today())->whereNull('deleted_at')->get();
        $repayments_today = DB::table('customer_loan_repayment')->whereDate('created_at','=',Carbon::today())->whereNull('deleted_at')->get();
        $routes = DB::table('route')->whereNull('deleted_at')->get();
        $s_expenses = DB::table('sales_rep_expenses')->whereDate('created_at','=',Carbon::today())->whereNull('deleted_at')->get();
        $o_expenses = DB::table('other_expenses')->whereDate('created_at','=',Carbon::today())->whereNull('deleted_at')->get();
        $expenses = $s_expenses->merge($o_expenses);
        $c_book_amount = DB::table('cash_book')->whereDate('created_at','=',Carbon::today())->orderBy('id', 'desc')->first();
        $b_book_amount = DB::table('bank_book')->whereDate('created_at','=',Carbon::today())->orderBy('id','desc')->first();
//        $c_book_amount = DB::table('cash_book')->whereDate('created_at','=',Carbon::today())->latest()->first();
        $c_book_amount?$c_book_amount=$c_book_amount->balance:$c_book_amount=0.00;
//        $b_book_amount = DB::table('bank_book')->whereDate('created_at','=',Carbon::today())->latest()->first();
        $b_book_amount?$b_book_amount=$b_book_amount->balance:$b_book_amount=0.00;
        $total_expenses = 0.0;
        $total_collection = 0.0;
        $other_arr = [];
        $new_loans_total = 0.0;
        //try to get all loans customer details by not using foreach
        foreach ($new_loans as $new_loan) {
            $rel_cus = DB::table('customer')->where('id','=',$new_loan->customer_id)->first();
            if ($rel_cus) {
                $new_loan->no = $rel_cus->customer_no;
                $new_loan->name = $rel_cus->name;
                $new_loan->nic = $rel_cus->NIC;
                $new_loan->route = DB::table('route')->where('id','=',$rel_cus->route_id)->select('name')->pluck('name')->first();
            }
            $comm = DB::table('sales_rep_commission')->where('loan_id','=',$new_loan->id)->first();
            if ($comm) {
                $new_loan->sRep = DB::table('users')->where('id','=',$comm->user_id)->select('name')->pluck('name')->first();
            }
            $new_loans_total+=$new_loan->total_loan_amount;
        }
        foreach ($repayments_today as $item) {
            $cust_loan = DB::table('customer_loan')->where('id','=',$item->loan_id)->first();
            $cust = DB::table('customer')->where('id','=',$cust_loan->customer_id)->first();
            $item->loan_no = $cust_loan->loan_no;
            $item->cust_no = $cust->customer_no;
            $item->name = $cust->name;
            $item->route_id = $cust->route_id;
            $total_collection+=$item->amount;
        }

        foreach ($expenses as $expens) {
            $total_expenses+=$expens->amount;
        }

        $other_arr['total_ex'] = $total_expenses;
        $other_arr['total_col'] = $total_collection;
        $other_arr['total_loan_amt'] = $new_loans_total;
        $other_arr['c_book_amt'] = $c_book_amount;
        $other_arr['b_book_amt'] = $b_book_amount;

        return response()->json([
            'error'=>false,
            'new_loans'=>$new_loans,
            'repayments'=>$repayments_today,
            'routes'=>$routes,
            'expenses'=>$expenses,
            'extras'=>$other_arr
        ]);
    }

    public function getDataByDate(Request $request) {
        $date = Carbon::parse($request['date']);
        $new_loans = DB::table('customer_loan')->whereDate('created_at','=',$date)->whereNull('deleted_at')->get();
        $repayments_today = DB::table('customer_loan_repayment')->whereDate('created_at','=',$date)->whereNull('deleted_at')->get();
        $routes = DB::table('route')->whereNull('deleted_at')->get();
        $s_expenses = DB::table('sales_rep_expenses')->whereDate('created_at','=',$date)->whereNull('deleted_at')->get();
        $o_expenses = DB::table('other_expenses')->whereDate('created_at','=',$date)->whereNull('deleted_at')->get();
        $expenses = $s_expenses->merge($o_expenses);
        $c_book_amount = DB::table('cash_book')->whereDate('created_at','=',$date)->orderBy('id', 'desc')->first();
        $b_book_amount = DB::table('bank_book')->whereDate('created_at','=',$date)->orderBy('id','desc')->first();
        $c_book_amount?$c_book_amount=$c_book_amount->balance:$c_book_amount=0.00;
        $b_book_amount?$b_book_amount=$b_book_amount->balance:$b_book_amount=0.00;
        $total_expenses = 0.0;
        $total_collection = 0.0;
        $other_arr = [];
        $new_loans_total = 0.0;
        //try to get all loans customer details by not using foreach
        foreach ($new_loans as $new_loan) {
            $rel_cus = DB::table('customer')->where('id','=',$new_loan->customer_id)->first();
            if ($rel_cus) {
                $new_loan->no = $rel_cus->customer_no;
                $new_loan->name = $rel_cus->name;
                $new_loan->nic = $rel_cus->NIC;
                $new_loan->route = DB::table('route')->where('id','=',$rel_cus->route_id)->select('name')->pluck('name')->first();
            }
            $comm = DB::table('sales_rep_commission')->where('loan_id','=',$new_loan->id)->first();
            if ($comm) {
                $new_loan->sRep = DB::table('users')->where('id','=',$comm->user_id)->select('name')->pluck('name')->first();
            }
            $new_loans_total+=$new_loan->total_loan_amount;
        }
        foreach ($repayments_today as $item) {
            $cust_loan = DB::table('customer_loan')->where('id','=',$item->loan_id)->first();
            $cust = DB::table('customer')->where('id','=',$cust_loan->customer_id)->first();
            $item->loan_no = $cust_loan->loan_no;
            $item->cust_no = $cust->customer_no;
            $item->name = $cust->name;
            $item->route_id = $cust->route_id;
            $total_collection+=$item->amount;
        }

        foreach ($expenses as $expens) {
            $total_expenses+=$expens->amount;
        }

        $other_arr['total_ex'] = $total_expenses;
        $other_arr['total_col'] = $total_collection;
        $other_arr['total_loan_amt'] = $new_loans_total;
        $other_arr['c_book_amt'] = $c_book_amount;
        $other_arr['b_book_amt'] = $b_book_amount;

        return response()->json([
            'error'=>false,
            'new_loans'=>$new_loans,
            'repayments'=>$repayments_today,
            'routes'=>$routes,
            'expenses'=>$expenses,
            'extras'=>$other_arr
        ]);
    }

    public function editRepaymentByNum(Request $request) {
        $date = $request['date'];
        $loan_no = $request['loan_no'];
        $new_amount = $request['new_amount'];

        $loan_id = DB::table('customer_loan')->where('loan_no','=',$loan_no)
            ->select('id')
            ->pluck('id');

        $record = DB::table('customer_loan_repayment')->where('loan_id','=',$loan_id)
            ->first();

        $prev_amount = $record->amount;
        $prev_remain_amount = $record->remaining_amount;
        $cash_book_id = $record->cash_book_id;
        $bank_book_id = $record->bank_book_id;

        $result = DB::table('customer_loan_repayment')->where('loan_id','=',$loan_id)
            ->update([
                'amount'=>$new_amount,
                'remaining_amount'=>$prev_remain_amount-$prev_amount+$new_amount
            ]);

        if ($result>0) {
            return response()->json([
                'error'=>false,
                'message'=>"updated successfully!"
            ]);
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"Edit failed!"
            ]);
        }
    }
}
