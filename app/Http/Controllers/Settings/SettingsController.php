<?php


namespace App\Http\Controllers\Settings;


use App\Cash_book;
use App\CustomerRange;
use App\Http\Controllers\Controller;
use App\Route;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function getSettingsData()
    {
        try {
            $user = Auth::user();
            $salesReps = DB::table('users')->where('role_id', '>', 1)->where('id', '!=', Auth::id())->whereNull('deleted_at')->get();
            $routes = DB::table('route')->whereNull('deleted_at')->get();
            $cash_balance = DB::table('cash_book')->orderBy('id','desc')->select('balance')->pluck('balance')->first();

            foreach ($salesReps as $salesRep) {
                $salesRep->route = $this->formatSalesRep($salesRep);
            }

            foreach ($routes as $route){
                $range = DB::table('customer_ranges')->where('route_id','=',$route->id)->whereNull('deleted_at')->first();
                $route->startNo = $range->start;
                $route->endNo = $range->end;
            }

            return response()->json([
                'error' => false,
                'user' => $user,
                'salesReps' => $salesReps,
                'routes' => $routes,
                'cash_balance'=>$cash_balance,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "error occurred!"
            ]);
        }

    }

    private function formatSalesRep($salesRep)
    {
        return DB::table('route')->where('id', '=', $salesRep->route_id)->whereNull('deleted_at')->select('name')->pluck('name')->first();
    }

    public function getSalesRepNumber()
    {
        $no = DB::table('users')->orderBy('id', 'desc')->select('id')->pluck('id')->first();

        return response()->json([
            'error' => false,
            'num' => $no + 1
        ]);
    }

    public function addNewSalesRep(Request $request)
    {
        $name = $request['name'];
        $email = $request['email'];
        $password = $request['password'];
        $profile_pic = "public/salesRep/avatar.jpg";
        $nic = $request['NIC'];
        $addLine1 = $request['addLine1'];
        $addLine2 = $request['addLine2'];
        $city = $request['city'];
        $role_id = 2;
        $route_id = $request['route_id'];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'NIC' => 'required|string|min:10|unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => TRUE,
                'errors' => $validator->errors(),
                'error_code' => 401
            ]);
        } else {
            try {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($password),
                    'profile_pic' => $profile_pic,
                    'NIC' => $nic,
                    'addLine1' => $addLine1,
                    'addLine2' => $addLine2,
                    'role_id' => $role_id,
                    'city' => $city,
                    'route_id' => $route_id
                ]);

                if ($user) {
                    $user->route = DB::table('route')->where('id', '=', $user->route_id)->whereNull('deleted_at')->select('name')->pluck('name')->first();
                    return response()->json([
                        'error' => FALSE,
                        'salesRep' => $user
                    ]);
                } else {
                    return response()->json([
                        'error' => TRUE,
                        'message' => "Unknown error occurred"
                    ]);
                }
            } catch (Exception $e) {
                return response()->json([
                    'error' => TRUE,
                    'message' => "Unknown error occurred"
                ]);
            }
        }
    }

    public function editSalesRep(Request $request)
    {
        $id = $request['id'];
        $name = $request['name'];
        $email = $request['email'];
        $nic = $request['NIC'];
        $addLine1 = $request['addLine1'];
        $addLine2 = $request['addLine2'];
        $city = $request['city'];
        $route_id = $request['route_id'];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'NIC' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => TRUE,
                'errors' => $validator->errors(),
                'error_code' => 401
            ]);
        } else {
            try {

                $emailPresent = $this->checkEmailPresent($email, $id);
                $nicPresent = $this->checkNicPresent($nic, $id);

                if ($emailPresent && $nicPresent) {
                    $count = DB::table('users')->where('id', '=', $id)->update([
                        'name' => $name,
                        'email' => $email,
                        'NIC' => $nic,
                        'addLine1' => $addLine1,
                        'addLine2' => $addLine2,
                        'city' => $city,
                        'route_id' => $route_id
                    ]);

                    if ($count > 0) {
                        $user = DB::table('users')->where('id', '=', $id)->whereNull('deleted_at')->first();
                        $user->route = DB::table('route')->where('id', '=', $user->route_id)->whereNull('deleted_at')->select('name')->pluck('name')->first();
                        return response()->json([
                            'error' => FALSE,
                            'salesRep' => $user
                        ]);
                    } else {
                        return response()->json([
                            'error' => TRUE,
                            'message' => "Unknown error occurred"
                        ]);
                    }
                } else {
                    return response()->json([
                        'error'=>true,
                        'emailPresent'=>$emailPresent,
                        'nicPresent'=>$nicPresent
                    ]);
                }
            } catch (Exception $e) {
                return response()->json([
                    'error' => TRUE,
                    'message' => "Unknown error occurred"
                ]);
            }
        }
    }

    private function checkEmailPresent($email, $id)
    {
        $record_id = DB::table('users')->where('email', '=', $email)->select('id')->pluck('id')->first();

        if ($record_id == $id) {
            return true;
        } else {
            return false;
        }
    }

    private function checkNicPresent($nic, $id)
    {
        $record_id = DB::table('users')->where('NIC', '=', $nic)->select('id')->pluck('id')->first();

        if ($record_id == $id) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteSalesRep(Request $request) {
        $id = $request['id'];

        $exists = DB::table('users')->where('id','=',$id)->whereNull('deleted_at')->exists();

        if ($exists) {
            $count = DB::table('users')->where('id','=',$id)->update([
                'deleted_at'=>Carbon::now()
            ]);

            if ($count>0) {
                return response()->json([
                    'error'=>false,
                    'message'=>"successfully deleted!"
                ]);
            } else {
                return response()->json([
                    'error'=>true,
                    'message'=>"delete fail, error occurred!"
                ]);
            }
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"Already deleted!"
            ]);
        }
    }

    private function getStartNoRouteRanges() {
        $last_end_number = DB::table('customer_ranges')->whereNull('deleted_at')->orderBy('end','dsc')
            ->select('end')
            ->pluck('end')
            ->first();

        return $last_end_number+1;
    }

    public function getRouteNumber() {
        $no = DB::table('route')->orderBy('id', 'desc')->select('id')->pluck('id')->first();

        $startNo = $this->getStartNoRouteRanges();
        $endNo = $startNo+1000;

        return response()->json([
            'error' => false,
            'num' => $no + 1,
            'startNo'=>$startNo,
            'endNo'=>$endNo
        ]);
    }

    public function deleteRoute (Request $request) {
        $id = $request['id'];

        $exists = DB::table('route')->where('id','=',$id)->whereNull('deleted_at')->exists();

        if ($exists) {
            $count = DB::table('route')->where('id','=',$id)->update([
                'deleted_at'=>Carbon::now()
            ]);

            $count1 = DB::table('customer_ranges')->where('route_id','=',$id)->update([
                'deleted_at'=>Carbon::now()
            ]);

            if ($count>0 && $count1) {
                return response()->json([
                    'error'=>false,
                    'message'=>"successfully deleted!"
                ]);
            } else {
                return response()->json([
                    'error'=>true,
                    'message'=>"delete fail, error occurred!"
                ]);
            }
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"Already deleted!"
            ]);
        }
    }

    public function editRoute (Request $request) {
        $id = $request['id'];
        $name = $request['name'];
        $startNo = $request['startNo'];
        $endNo = $request['endNo'];

        $count = DB::table('route')->where('id','=',$id)->update([
            'name'=>$name,
            'updated_at'=>Carbon::now()
        ]);

        $count1 = DB::table('customer_ranges')->where('route_id','=',$id)->update([
            'start'=>$startNo,
            'end'=>$endNo,
            'updated_at'=>Carbon::now()
        ]);

        if ($count>0 && $count1) {
            return response()->json([
                'error'=>false,
                'message'=>"Updated successfully"
            ]);
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"Error occurred!"
            ]);
        }
    }

    public function addNewRoute (Request $request) {
        $name = $request['name'];
        $startNo = $request['startNo'];
        $endNo = $request['endNo'];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'startNo'=>'required',
            'endNo'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => TRUE,
                'errors' => $validator->errors(),
                'error_code' => 401
            ]);
        } else {
            try {
                $route = Route::create([
                    'name' => $name,
                ]);

                if ($route) {

                    $range = $this->addCustomerRanges($route->id, $startNo, $endNo);

                    $route->startNo = $range->start;
                    $route->endNo = $range->end;

                    return response()->json([
                        'error' => FALSE,
                        'route' => $route
                    ]);
                } else {
                    return response()->json([
                        'error' => TRUE,
                        'message' => "Unknown error occurred"
                    ]);
                }
            } catch (Exception $e) {
                return response()->json([
                    'error' => TRUE,
                    'message' => "Unknown error occurred"
                ]);
            }
        }
    }

    private function addCustomerRanges($route_id, $startNo, $endNo) {
        try{
            $customer_range = new CustomerRange();
            $customer_range->route_id = $route_id;
            $customer_range->start = $startNo;
            $customer_range->end = $endNo;
            $customer_range->save();

            return $customer_range;
        } catch (Exception $e){
            return null;
        }
    }

    public function cashBookAmountUpdate(Request $request) {
        $amount = $request['amount'];

        $records = DB::table('cash_book')->get();

        $count = $records->count();

        $balance = $records->pluck('balance')->last();

        if (!$count>0 && !$balance>0) {
            $cash_book = Cash_book::create([
                'transaction_date' => Carbon::now()->format('Y-m-d'),
                'description'=> "Opening balance",
                'deposit'=>$amount,
                'withdraw'=>0,
                'balance'=>$amount
            ]);

            return response()->json([
                'error'=>false,
                'message'=>"Successfully updated opening balance!"
            ]);
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"Already updated opening balance!"
            ]);
        }
    }
}
