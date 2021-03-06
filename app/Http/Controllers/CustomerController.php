<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function createCustomer(Request $request){
        $validate = Validator::make ($request->all (),[
            'name' => 'required|string|max:255',
            'customer_no'=>'required|unique:customer',
            'nic'=>'required|string|min:10',
            'contact_no'=>'required|regex:/[0-9]{9}/',
            'addLine1'=>'required',
            'addLine2'=>'required',
        ]);

        if($validate->fails ()){
            return redirect ('/createCustomer')
                ->withErrors ($validate)
                ->withInput ();
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
                    'salesRep_id'=>$request->salesRep_id,
                    'route_id'=>$request->route_id
                ]);
                if ($customer){
                    return redirect ('/createCustomer')->with ('message','Customer created successfully');
                }
            }catch (ModelNotFoundException $e){
                return redirect ('/createCustomer')->with ('error','Error occurred while creating customer');
            }

        }

    }

    public function getCustomers() {
        try{
            $customers = DB::table ('customer')->get();
            if(!$customers->isEmpty ()){
                return view ('Test.customerNumbers',['error'=>false, 'customers'=> $customers]);
            } else {
                return view ('Test.customerNumbers',['error'=>true, 'message'=>"Customer List Empty"]);
            }
        }catch (\Exception $e) {
            return view ('Test.customerNumbers',['error'=>true, 'message'=>"Unknown error occurred"]);
        }
    }
}
