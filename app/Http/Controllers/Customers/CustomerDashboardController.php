<?php
/**
 * Created by PhpStorm.
 * User: yachitha
 * Date: 2/2/19
 * Time: 12:49 PM
 */

namespace App\Http\Controllers\Customers;



use App\Customer;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Customers\CustomerController;

class CustomerDashboardController
{
    protected $customerController;

    public function __construct(CustomerController $controller)
    {
        $this->customerController = $controller;
    }

    private function getAllCustomersWithRoute($routes) {
        $customers=[];
        if (!empty($routes)) {
            foreach ($routes as $route) {
                $customerSet = $this->customerController->getCustomersByRouteId($route->id);
                $item = [
                    'route_id'=>$route->id,
                    'customers'=>$customerSet
                ];
                $customers[] = $item;
            }
        }
        return $customers;
    }

    public function addCustomer(Request $request) {
        $validate = Validator::make ($request->all (),[
            'name' => 'required|string|max:255',
            'customer_no'=>'required|unique:customer',
            'nic'=>'required|string|min:10',
            'contact_no'=>'required|regex:/[0-9]{10}/',
            'addLine1'=>'required',
            'addLine2'=>'required',
        ]);

        if($validate->fails ()){
            return response()->json([
                'error'=>true,
                'message'=>$validate->errors()
            ]);
        }else{
            try{
                $customer = Customer::create([
                    'customer_no'=>$request->customer_no,
                    'name' => $request->name,
                    'NIC'=>$request->nic,
                    'addLine1'=>$request->addLine1,
                    'addLine2'=>$request->addLine2,
                    'city'=>$request->city,
                    'contact_no'=>$request->contact_no,
                    'status'=>"active",
                    'salesRep_id'=>1,
                    'route_id'=>$request->route_id
                ]);
                if ($customer){
                    $monthlyGrowth = $this->customerController->getMonthlyGrowth();
                    $count = $this->customerController->getTotalCustomers();
                    return response()->json([
                        'error'=>false,
                        'message'=>"customer added successfully",
                        'customer'=>$customer,
                        'monthlyGrowth'=>$monthlyGrowth,
                        'count'=>$count
                    ]);
                }
            }catch (Exception $e){
                return response()->json([
                    'error'=>true,
                    'message'=>$e
                ]);
            }

        }
    }

    public function getCustomerData () {
        try {
            $salesReps = DB::table('users')->where('role_id','2')->get(['id','route_id']);
            $routes = DB::table('route')->get();
            $customers = $this->getAllCustomersWithRoute($routes);
            $monthlyGrowth = $this->customerController->getMonthlyGrowth();
            $count = $this->customerController->getTotalCustomers();
            return response()->json([
                'error'=>false,
                'salesReps'=>$salesReps,
                'routes'=>$routes,
                'customers'=>$customers,
                'monthlyGrowth'=>$monthlyGrowth,
                'count'=>$count
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error'=>true,
                'message'=> "error occurred!"
            ]);
        }
    }

    public function getCustomerNumber() {
        $customer_no = DB::table('customer')->orderBy('customer_no', 'desc')->select('customer_no')->pluck('customer_no')->first();

        return response()->json([
            'error' => false,
            'customer_no' => $customer_no + 1
        ]);
    }

    public function editCustomer(Request $request) {
        $customer_id = DB::table('customer')->where('customer_no','=',$request['customer_no'])->select('id')->pluck('id')->first();
        $customer_name = $request['name'];
        $nic = $request['nic'];
        $contact_no = $request['contact_no'];
        $route_id = $request['route_id'];
        $addLine1 = $request['addLine1'];
        $addLine2 = $request['addLine2'];
        $city = $request['city'];

        $result = DB::table('customer')->where('id','=',$customer_id)->update([
            'name'=>$customer_name,
            'NIC'=>$nic,
            'contact_no'=>$contact_no,
            'route_id'=>$route_id,
            'addLine1'=>$addLine1,
            'addLine2'=>$addLine2,
            'city'=>$city
        ]);

        if ($result>0) {
            $customer = DB::table('customer')->where('id','=',$customer_id)->first();

            return response()->json([
                'error'=>false,
                'message'=>"successfully edit the customer",
                'customer'=>$customer
            ]);
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"failed to edit the customer"
            ]);
        }
    }

    public function deleteCustomer(Request $request) {
        $result = DB::table('customer')->where('customer_no','=',$request['customer_no'])->update([
            'deleted_at'=>Carbon::now()
        ]);

        if ($result>0) {
            return response()->json([
                'error'=>false,
                'message'=>"deleted successfully!"
            ]);
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"delete fail"
            ]);
        }
    }

    public function getCustomersToDisable() {
        $d_cus = DB::table('customer')->where('status','!=',"disable")->whereNull('deleted_at')->get();

        if ($d_cus->count()>0) {
            return response()->json([
                'error'=>false,
                'd_cus'=>$d_cus
            ]);
        } else {
            return response()->json([
                'error'=>true,
                'message'=>"Error!"
            ]);
        }
    }
}
