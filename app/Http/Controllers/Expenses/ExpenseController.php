<?php
/**
 * Created by PhpStorm.
 * User: Yachitha Sandaruwan
 * Date: 12/9/2018
 * Time: 11:23 PM
 */

namespace App\Http\Controllers\Expenses;


use App\Bank_book;
use App\Cash_book;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    function filterRecords()
    {
    }

    public function getExpenseData(Request $request)
    {
        $start_date = Carbon::parse($request['startDate']);
        $end_date = Carbon::parse($request['endDate']);

        if ($start_date->eq($end_date)) {
            $records = DB::table('sales_rep_expenses')->whereDate('created_at', '=', $start_date)->whereNull('deleted_at')->get()
                ->merge(DB::table('other_expenses')->whereDate('created_at', '=', $start_date)->whereNull('deleted_at')->get());
            $routes = DB::table('route')->whereNull('deleted_at')->get();
            $salesReps = DB::table('users')->where('role_id', '=', 2)->whereNull('deleted_at')->get();
            return response()->json([
                'error' => false,
                'records' => $this->formatRecords($records),
                'routes' => $routes,
                'eNames' => $this->getENames(),
                'salesReps' => $salesReps
            ]);
        } elseif ($end_date->gt($start_date)) {
            $records = DB::table('sales_rep_expenses')->whereBetween('created_at', [$start_date, $end_date])->whereNull('deleted_at')->get()
                ->merge(DB::table('other_expenses')->whereBetween('created_at', [$start_date, $end_date])->whereNull('deleted_at')->get());
            $routes = DB::table('route')->whereNull('deleted_at')->get();
            $salesReps = DB::table('users')->where('role_id', '>', 1)->whereNull('deleted_at')->get();
            return response()->json([
                'error' => false,
                'records' => $this->formatRecords($records),
                'routes' => $routes,
                'eNames' => $this->getENames(),
                'salesReps' => $salesReps
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => "end date must be greater than start date!"
            ]);
        }
    }

    private function formatRecords($records)
    {
        foreach ($records as $record) {
            if (!empty($record->sales_rep_id)) {
                $route_id = DB::table('users')->where('id', '=', $record->sales_rep_id)->whereNull('deleted_at')->select('route_id')->pluck('route_id')->first();
                $record->route = DB::table('route')->where('id', '=', $route_id)->whereNull('deleted_at')->select('name')->pluck('name')->first();
                $record->isSalesRep = true;
                $record->user_name = DB::table('users')->where('id', '=', $record->sales_rep_id)->select('name')->pluck('name')->first();
            } else {
                $route_id = DB::table('users')->where('id', '=', $record->user_id)->whereNull('deleted_at')->select('route_id')->pluck('route_id')->first();
                $record->route = DB::table('route')->where('id', '=', $route_id)->whereNull('deleted_at')->select('name')->pluck('name')->first();
                $record->isSalesRep = false;
                $record->user_name = DB::table('users')->where('id', '=', $record->user_id)->select('name')->pluck('name')->first();
            }
            $result = explode("#", $record->description);
            $record->description = $result[1];
            $record->exName = $result[0];
            $record->cash_amount = $this->getCashAmount($record->cash_book_id);
            $arr = $this->getBankAmount($record->bank_book_id);
            $record->bank_amount = $arr[0];
            $record->cheque_no = $arr[1];
        }
        return $records;
    }

    private function getCashAmount($id)
    {
        $cash_amount = 0;

        if (!empty($id)) {
            $cash_amount = DB::table('cash_book')->where('id', '=', $id)->select('withdraw')->pluck('withdraw')->first();
        }

        return $cash_amount;
    }

    private function getBankAmount($id)
    {
        $bank_amount = 0;
        $cheque_no = 0;

        if (!empty($id)) {
            $bank_amount = DB::table('bank_book')->where('id', '=', $id)->select('withdraw')->pluck('withdraw')->first();
            $cheque_no = DB::table('bank_book')->where('id', '=', $id)->select('cheque_no')->pluck('cheque_no')->first();
        }

        return [$bank_amount, $cheque_no];
    }

    private function getENames()
    {
        $other_expense_descriptions = DB::table('other_expenses')->whereNull('deleted_at')->select('description')->pluck('description');
        $sales_rep_expenses_descriptions = DB::table('sales_rep_expenses')->whereNull('deleted_at')->select('description')->pluck('description');

        $final_descriptions = $other_expense_descriptions->merge($sales_rep_expenses_descriptions);

        $eNames = $this->divideENames($final_descriptions);

        return $eNames;
    }

    private function divideENames($descriptions)
    {
        $eNames = [];

        foreach ($descriptions as $description) {
            $eNames[] = explode("#", $description)[0];
        }

        return $eNames;
    }

    public function getExpenseDataByRoute(Request $request)
    {
        $start_date = Carbon::parse($request['startDate']);
        $end_date = Carbon::parse($request['endDate']);
        $route_id = $request['route_id'];

        if ($route_id > 0) {
            $user_id_list = DB::table('users')->where('route_id', '=', $route_id)->whereNull('deleted_at')->select('id')->pluck('id');

            $recordsByRoute = [];

            foreach ($user_id_list as $id) {
                if ($start_date->eq($end_date)) {
                    $recordsByRoute[] = DB::table('sales_rep_expenses')->whereDate('created_at', '=', $start_date)->where('sales_rep_id', '=', $id)->whereNull('deleted_at')->get()
                        ->merge(DB::table('other_expenses')->whereDate('created_at', '=', $start_date)->where('user_id', '=', $id)->whereNull('deleted_at')->get());
                } elseif ($end_date->gt($start_date)) {
                    $recordsByRoute[] = DB::table('sales_rep_expenses')->whereBetween('created_at', [$start_date, $end_date])->where('sales_rep_id', '=', $id)->whereNull('deleted_at')->get()
                        ->merge(DB::table('other_expenses')->whereBetween('created_at', [$start_date, $end_date])->where('user_id', '=', $id)->whereNull('deleted_at')->get());
                }
            }

            return response()->json([
                'error' => false,
                'records' => $this->formatRecords($recordsByRoute[0])
            ]);

        } else {
            if ($start_date->eq($end_date)) {
                $records = DB::table('sales_rep_expenses')->whereDate('created_at', '=', $start_date)->whereNull('deleted_at')->get()
                    ->merge(DB::table('other_expenses')->whereDate('created_at', '=', $start_date)->whereNull('deleted_at')->get());
                return response()->json([
                    'error' => false,
                    'records' => $this->formatRecords($records)
                ]);
            } elseif ($end_date->gt($start_date)) {
                $records = DB::table('sales_rep_expenses')->whereBetween('created_at', [$start_date, $end_date])->whereNull('deleted_at')->get()
                    ->merge(DB::table('other_expenses')->whereBetween('created_at', [$start_date, $end_date])->whereNull('deleted_at')->get());
                return response()->json([
                    'error' => false,
                    'records' => $this->formatRecords($records)
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "end date must be greater than start date!"
                ]);
            }
        }

    }

    public function getSalesRepExpenseNumber()
    {
        $final_id = DB::table('sales_rep_expenses')->orderBy('id', 'desc')->select('id')->pluck('id')->first();

        return response()->json([
            'error' => false,
            'sales_rep_expense_no' => $final_id + 1
        ]);
    }

    public function getOtherExpenseNumber()
    {
        $final_id = DB::table('other_expenses')->orderBy('id', 'desc')->select('id')->pluck('id')->first();

        return response()->json([
            'error' => false,
            'other_expense_no' => $final_id + 1
        ]);
    }

    public function addSalesRepExpense(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'tDate' => 'required|string',
            'eName' => 'required',
            'cash_amount' => 'required',
            'bank_amount' => 'required',
            'cheque_no' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors()
            ]);
        } else {
            $transaction_date = $request['tDate'];
            $eName = $request['eName'];
            $description = $request['description'];
            $cash_amount = $request['cash_amount'];
            $bank_amount = $request['bank_amount'];
            $cheque_no = $request['cheque_no'];
            $salesRep_id = $request['salesRep'];

            $salesRep_name = DB::table('users')->where('id', '=', $salesRep_id)->select('name')->pluck('name')->first();
            $expense_description = $this->formatDescription($eName, $description);

            $cash_withdraw_id = null;
            $bank_withdraw_id = null;
            if ($cash_amount != 0) {
                $cash_withdraw_id = $this->addCashBookRecord($salesRep_name, $cash_amount, $expense_description);
            }
            if ($bank_amount != 0) {
                $bank_withdraw_id = $this->addBankBookRecord($salesRep_name, $bank_amount, $cheque_no, $expense_description);
            }

            $id = DB::table('sales_rep_expenses')->insertGetId([
                'transaction_date' => $transaction_date,
                'description' => $expense_description,
                'amount' => $cash_amount + $bank_amount,
                'sales_rep_id' => $salesRep_id,
                'cash_book_id' => $cash_withdraw_id,
                'bank_book_id' => $bank_withdraw_id,
                'created_at' => Carbon::now()
            ]);

            $new_expense = DB::table('sales_rep_expenses')->where('id', '=', $id)->first();

            return response()->json([
                'error' => false,
                'record' => $this->formatExpenseRecord($new_expense)
            ]);
        }
    }

    private function formatDescription($eName, $description)
    {
        return $eName . "#" . $description;
    }

    private function addCashBookRecord($user_name, $cash_amount, $description)
    {
        try {
            $cash_withdraw = new Cash_book();
            $cash_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $cash_withdraw->description = $user_name . " spends for " . $description;
            $cash_withdraw->deposit = 0.0;
            $cash_withdraw->withdraw = $cash_amount;
            $last_balance_row = DB::table('cash_book')->orderBy('id', 'desc')->first();
            if ($last_balance_row) {
                $cash_withdraw->balance = $last_balance_row->balance - $cash_amount;
            } else {
                $cash_withdraw->balance = -$cash_amount;
            }

            $cash_withdraw->save();
            return $cash_withdraw->id;

        } catch (Exception $e) {
            return null;
        }
    }

    private function addBankBookRecord($user_name, $bank_amount, $cheque_no, $description)
    {
        try {
            $bank_withdraw = new Bank_book();
            $bank_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $bank_withdraw->description = $user_name . " spends for " . $description;
            $bank_withdraw->deposit = 0.0;
            $bank_withdraw->withdraw = $bank_amount;
            $last_balance_row = DB::table('bank_book')->orderBy('id', 'desc')->first();
            if ($last_balance_row) {
                $bank_withdraw->balance = $last_balance_row->balance - $bank_amount;
            } else {
                $bank_withdraw->balance = -$bank_amount;
            }
            $bank_withdraw->cheque_no = $cheque_no;

            $bank_withdraw->save();

            return $bank_withdraw->id;
        } catch (Exception $e) {
            return null;
        }
    }

    private function formatExpenseRecord($record)
    {

        if (!empty($record->sales_rep_id)) {
            $route_id = DB::table('users')->where('id', '=', $record->sales_rep_id)->whereNull('deleted_at')->select('route_id')->pluck('route_id')->first();
            $record->route = DB::table('route')->where('id', '=', $route_id)->whereNull('deleted_at')->select('name')->pluck('name')->first();
            $record->isSalesRep = true;
        } else {
            $route_id = DB::table('users')->where('id', '=', $record->user_id)->whereNull('deleted_at')->select('route_id')->pluck('route_id')->first();
            $record->route = DB::table('route')->where('id', '=', $route_id)->whereNull('deleted_at')->select('name')->pluck('name')->first();
            $record->isSalesRep = false;
        }
        $result = explode("#", $record->description);
        $record->description = $result[0] . " " . $result[1];
        $record->exName = $result[0];
        $record->cash_amount = $this->getCashAmount($record->cash_book_id);
        $arr = $this->getBankAmount($record->bank_book_id);
        $record->bank_amount = $arr[0];
        $record->cheque_no = $arr[1];

        return $record;
    }

    public function editSalesRepExpense(Request $request)
    {
        $id = $request['id'];
        $tDate = $request['tDate'];
        $eName = $request['eName'];
        $description = $request['description'];
        $cash_amount = $request['cash_amount'];
        $bank_amount = $request['bank_amount'];
        $cheque_no = $request['cheque_no'];
        $salesRep_id = $request['salesRep_id'];

        $old_expense = DB::table('sales_rep_expenses')->where('id', '=', $id)->whereNull('deleted_at')->first();
        $salesRep = DB::table('user')->where('id','=',$salesRep_id)->whereNull('deleted_at')->first();
        $salesRep_name = $salesRep->name;

        $new_bank_id = null;
        $new_cash_id = null;

        if (!empty($old_expense)) {
            if ($old_expense->cash_book_id != null) {
                $new_cash_id = $this->createAdjustmentToCashBook($old_expense->cash_book_id, $salesRep_name, $cash_amount);
            }
            if ($old_expense->bank_book_id != null) {
                $new_bank_id = $this->createAdjustmentToBankBook($old_expense->bank_book_id, $salesRep_name, $bank_amount, $cheque_no);
            }



            DB::table('sales_rep_expenses')->where('id', '=', $id)->update([
                'transaction_date' => $tDate,
                'description' => $eName,//TODO change logic with adding eName table
                'amount' => $cash_amount + $bank_amount,
                'sales_rep_id' => $salesRep_id,
                'cash_book_id' => $new_cash_id,
                'bank_book_id' => $new_bank_id,
                'updated_at' => Carbon::now()
            ]);

            $expense = DB::table('sales_rep_expenses')->where('id', '=', $id)->first();

            return response()->json([
                'error' => false,
                'expense' => $this->formatExpenseRecord($expense)
            ]);

        } else {
            return response()->json([
                'error' => true,
                'message' => "Expense have been deleted!"
            ]);
        }
    }

    public function addOtherExpense(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'tDate' => 'required|string',
            'eName' => 'required',
            'cash_amount' => 'required',
            'bank_amount' => 'required',
            'cheque_no' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors()
            ]);
        } else {
            $transaction_date = $request['tDate'];
            $eName = $request['eName'];
            $description = $request['description'];
            $cash_amount = $request['cash_amount'];
            $bank_amount = $request['bank_amount'];
            $cheque_no = $request['cheque_no'];
            $user_id = Auth::id();

            $user_name = DB::table('users')->where('id', '=', $user_id)->select('name')->pluck('name')->first();
            $expense_description = $this->formatDescription($eName, $description);

            $cash_withdraw_id = null;
            $bank_withdraw_id = null;
            if ($cash_amount != 0) {
                $cash_withdraw_id = $this->addCashBookRecord($user_name, $cash_amount, $expense_description);
            }
            if ($bank_amount != 0) {
                $bank_withdraw_id = $this->addBankBookRecord($user_name, $bank_amount, $cheque_no, $expense_description);
            }

            $id = DB::table('other_expenses')->insertGetId([
                'transaction_date' => $transaction_date,
                'description' => $expense_description,
                'amount' => $cash_amount + $bank_amount,
                'user_id' => $user_id,
                'cash_book_id' => $cash_withdraw_id,
                'bank_book_id' => $bank_withdraw_id,
                'created_at' => Carbon::now()
            ]);

            $new_expense = DB::table('other_expenses')->where('id', '=', $id)->first();

            return response()->json([
                'error' => false,
                'record' => $this->formatExpenseRecord($new_expense)
            ]);
        }
    }

    public function editOtherExpense(Request $request)
    {
        $id = $request['id'];
        $tDate = $request['tDate'];
        $eName = $request['eName'];
        $description = $request['description'];
        $cash_amount = $request['cash_amount'];
        $bank_amount = $request['bank_amount'];
        $cheque_no = $request['cheque_no'];

        $old_expense = DB::table('other_expenses')->where('id', '=', $id)->whereNull('deleted_at')->first();
        $admin = Auth::user()->name;

        $new_bank_id = null;
        $new_cash_id = null;

        if (!empty($old_expense)) {
            if ($old_expense->cash_book_id != null) {
                $new_cash_id = $this->createAdjustmentToCashBook($old_expense->cash_book_id, $admin, $cash_amount);
            }
            if ($old_expense->bank_book_id != null) {
                $new_bank_id = $this->createAdjustmentToBankBook($old_expense->bank_book_id, $admin, $bank_amount, $cheque_no);
            }



            DB::table('other_expenses')->where('id', '=', $id)->update([
                'transaction_date' => $tDate,
                'description' => $eName."#",//TODO update system to eName table and correct the logic
                'amount' => $cash_amount + $bank_amount,
                'user_id' => Auth::id(),
                'cash_book_id' => $new_cash_id,
                'bank_book_id' => $new_bank_id,
                'updated_at' => Carbon::now()
            ]);

            $expense = DB::table('other_expenses')->where('id', '=', $id)->first();

            return response()->json([
                'error' => false,
                'expense' => $this->formatExpenseRecord($expense)
            ]);

        } else {
            return response()->json([
                'error' => true,
                'message' => "Expense have been deleted!"
            ]);
        }
    }

    private function createAdjustmentToCashBook($old_entry_id, $admin, $cash_amount)
    {
        $old_amount = DB::table('cash_book')->where('id', $old_entry_id)->select('withdraw')->pluck('withdraw')->first();
        if ($old_amount != $cash_amount) {
            try {
                $cash_deposit = new Cash_book();
                $cash_deposit->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
                $cash_deposit->description = "expense amount adjust by " . $admin;
                $cash_deposit->deposit = $old_amount;
                $cash_deposit->withdraw = 0.0;
                $last_balance_row = DB::table('cash_book')->orderBy('id', 'desc')->first();
                if ($last_balance_row) {
                    $cash_deposit->balance = $last_balance_row->balance + $old_amount;
                } else {
                    $cash_deposit->balance = +$old_amount;
                }

                $cash_deposit->save();

                $cash_withdraw = new Cash_book();
                $cash_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
                $cash_withdraw->description = "expense amount adjust by " . $admin;
                $cash_withdraw->deposit = 0.0;
                $cash_withdraw->withdraw = $cash_amount;
                $last_balance_row = DB::table('cash_book')->orderBy('id', 'desc')->first();
                if ($last_balance_row) {
                    $cash_withdraw->balance = $last_balance_row->balance - $cash_amount;
                } else {
                    $cash_withdraw->balance = -$cash_amount;
                }

                $cash_withdraw->save();

                return $cash_withdraw->id;
            } catch (Exception $e) {
                return null;
            }
        } else {
            return $old_entry_id;
        }
    }

    private function createAdjustmentToBankBook($old_entry_id, $admin, $bank_amount, $cheque_no)
    {
        $old_amount = DB::table('bank_book')->where('id', $old_entry_id)->select('withdraw')->pluck('withdraw')->first();
        $old_cheque_no = DB::table('bank_book')->where('id', $old_entry_id)->select('cheque_no')->pluck('cheque_no')->first();
        if ($old_amount != $bank_amount) {
            try {
                $bank_deposit = new Bank_book();
                $bank_deposit->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
                $bank_deposit->description = "expense adjust by " . $admin . " cheque number: " . $old_cheque_no;
                $bank_deposit->deposit = $old_amount;
                $bank_deposit->withdraw = 0.0;
                $last_balance_row = DB::table('bank_book')->orderBy('id', 'desc')->first();
                if ($last_balance_row) {
                    $bank_deposit->balance = $last_balance_row->balance + $old_amount;
                } else {
                    $bank_deposit->balance = +$old_amount;
                }
                $bank_deposit->cheque_no = $old_cheque_no;
                $bank_deposit->save();

                $bank_withdraw = new Bank_book();
                $bank_withdraw->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
                $bank_withdraw->description = "expense adjust by " . $admin . " cheque number: " . $cheque_no;
                $bank_withdraw->deposit = 0.0;
                $bank_withdraw->withdraw = $bank_amount;
                $last_balance_row = DB::table('bank_book')->orderBy('id', 'desc')->first();
                if ($last_balance_row) {
                    $bank_withdraw->balance = $last_balance_row->balance - $bank_amount;
                } else {
                    $bank_withdraw->balance = -$bank_amount;
                }
                $bank_withdraw->cheque_no = $cheque_no;
                $bank_withdraw->save();

                return $bank_withdraw->id;
            } catch (Exception $e) {
                return null;
            }
        } else {
            return $old_entry_id;
        }
    }

    private function checkCashAmountUpdate($id, $amount)
    {
        $record = DB::table('cash_book')->where('id', '=', $id)->whereNull('deleted_at')->first();
        $need_update = false;
        if (!empty($record)) {
            $withdraw = $record->withdraw;
            if ($withdraw != $amount) {
                $need_update = true;
            }
        }

        return $need_update;
    }

    private function checkBankAmountUpdate($id, $amount, $cheque_no)
    {
        $record = DB::table('bank_book')->where('id', '=', $id)->whereNull('deleted_at')->first();
        $need_update = false;
        if (!empty($record)) {
            $withdraw = $record->withdraw;
            $no = $record->cheque_no;
            if ($withdraw != $amount || $no != $cheque_no) {
                $need_update = true;
            }
        }

        return $need_update;
    }

    public function deleteOtherExpense(Request $request) {
        $id = $request['id'];
        $expense = DB::table('other_expenses')->where('id','=',$id)->whereNull('deleted_at')->first();
        if (!empty($expense)) {
            $admin = Auth::user()->name;
            $cash_book_id = $expense->cash_book_id;
            $bank_book_id = $expense->bank_book_id;

            if ($cash_book_id!=null) {
                $this->cashBookDeletionEntry($cash_book_id,$admin);
            } else {
                $this->bankBookDeletionEntry($bank_book_id,$admin);
            }
            $count = DB::table('other_expenses')->where('id','=',$id)->update([
                'deleted_at'=>Carbon::now()
            ]);
            if ($count>0) {
                return response()->json([
                    'error'=>false,
                    'message'=>"Deleted successfully"
                ]);
            } else {
                return response()->json([
                    'error'=>true,
                    'message'=>"deletion failed"
                ]);
            }
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"Already Deleted"
            ]);
        }
    }

    public function deleteSalesRepExpense(Request $request) {
        $id = $request['id'];
        $expense = DB::table('sales_rep_expenses')->where('id','=',$id)->whereNull('deleted_at')->first();
        if (!empty($expense)) {
            $admin = Auth::user()->name;
            $cash_book_id = $expense->cash_book_id;
            $bank_book_id = $expense->bank_book_id;

            if ($cash_book_id!=null) {
                $this->cashBookDeletionEntry($cash_book_id,$admin);
            } else {
                $this->bankBookDeletionEntry($bank_book_id,$admin);
            }
            $count = DB::table('sales_rep_expenses')->where('id','=',$id)->update([
                'deleted_at'=>Carbon::now()
            ]);
            if ($count>0) {
                return response()->json([
                    'error'=>false,
                    'message'=>"Deleted successfully"
                ]);
            } else {
                return response()->json([
                    'error'=>true,
                    'message'=>"deletion failed"
                ]);
            }
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"Already Deleted sr"
            ]);
        }
    }

    private function cashBookDeletionEntry($old_cash_book_id, $admin) {
        try {
            $old_entry = DB::table('cash_book')->where('id','=',$old_cash_book_id)->first();
            $amount = $old_entry->withdraw;
            $cash_deletion_entry = new Cash_book();
            $cash_deletion_entry->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $cash_deletion_entry->description = "deleted the expense by " . $admin;
            $cash_deletion_entry->deposit = $amount;
            $cash_deletion_entry->withdraw = 0.0;
            $last_balance_row = DB::table('cash_book')->orderBy('id', 'desc')->first();
            if ($last_balance_row) {
                $cash_deletion_entry->balance = $last_balance_row->balance + $amount;
            } else {
                $cash_deletion_entry->balance = +$amount;
            }

            $cash_deletion_entry->save();

            return $cash_deletion_entry->id;
        } catch (Exception $e) {
            return null;
        }
    }

    private function bankBookDeletionEntry($old_bank_book_id, $admin) {

        try{
            $old_entry = DB::table('bank_book')->where('id','=',$old_bank_book_id)->first();
            $amount = $old_entry->withdraw;
            $bank_deletion_entry = new Bank_book();
            $bank_deletion_entry->transaction_date = Carbon::now()->format("Y-m-d H:i:s");
            $bank_deletion_entry->description = "delete the expense by " . $admin . " under cheque number: " . $old_entry->cheque_no;
            $bank_deletion_entry->deposit = $amount;
            $bank_deletion_entry->withdraw = 0.0;
            $last_balance_row = DB::table('bank_book')->orderBy('id', 'desc')->first();
            if ($last_balance_row) {
                $bank_deletion_entry->balance = $last_balance_row->balance + $amount;
            } else {
                $bank_deletion_entry->balance = +$amount;
            }
            $bank_deletion_entry->cheque_no = null;
            $bank_deletion_entry->save();

            return $bank_deletion_entry->id;
        } catch (Exception $e) {
            return null;
        }
    }
}
