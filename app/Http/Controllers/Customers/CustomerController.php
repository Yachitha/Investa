<?php
/**
 * Created by PhpStorm.
 * User: Yachitha Sandaruwan
 * Date: 12/9/2018
 * Time: 9:42 AM
 */

namespace App\Http\Controllers\Customers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomersByRoute(Request $request) {
        try{
            $route_id = $request['route_id'];
            if (!empty($route_id)) {
                $customers = DB::table ('customer')->where ('route_id',$route_id)->get ();
                return response ()->json (['error'=>false, 'customers'=>$customers]);
            } else {
                return response ()->json (['error'=>true,'message'=>"no route specified"],500);
            }
        } catch(\Exception $e) {
            return response ()->json (['error'=>true, 'message'=>"Exception"],500);
        }
    }

    public function getCustomersByRouteId($route_id) {
        try{
            if (!empty($route_id)) {
                $customers = DB::table ('customer')->where ('route_id',$route_id)->get ();
                return $customers;
            } else {
                return [];
            }
        } catch(\Exception $e) {
            return [];
        }
    }

    public function getMonthlyGrowth() {
        try{
            $currentMonthCusCount = DB::table('customer')->whereMonth('created_at', '=', Carbon::now()->month)->get()->count();
            $lastMonthCusCount = DB::table('customer')->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->get()->count();

            $result = 0.0;
            if($lastMonthCusCount>0) {
                $result = (($currentMonthCusCount-$lastMonthCusCount)/$lastMonthCusCount)*100;
            } elseif ($lastMonthCusCount == 0) {
                $result = 100.0;
            }
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTotalCustomers() {
        try {
            $count = DB::table('customer')->get()->count();
            return $count;
        } catch (\Exception $e) {
            return null;
        }
    }
}
