<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $profile_pic = $request->profile_pic;
        $nic = $request->nic;
        $addLine1 = $request->addLine1;
        $addLine2 = $request->addLine2;
        $city = $request->city;
        $commission_id = $request->commission_id;
        $calender_id = $request->calender_id;
        $role_id = $request->role_id;
        $route_id = $request->route_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id'=>'required|string|max:255',
            'password'=>'required|string|min:6',
            'nic'=>'required|string|min:10'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'error'=>TRUE,
                'errors'=>$validator->errors(),
                'error_code'=>401
            ]);
        }else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'profile_pic'=>$profile_pic,
                'NIC'=>$nic,
                'addLine1'=>$addLine1,
                'addLine2'=>$addLine2,
                'role_id' =>$role_id,
                'city'=>$city,
                'commission_id'=>$commission_id,
                'calendar_id'=>$calender_id,
                'route_id'=>$route_id
            ]);

            if($user)
            {
                return response()->json([
                    'error'=>FALSE,
                    'user'=>$user
                ]);
            }
            else{
                return response()->json([
                    'error'=>TRUE,
                    'message'=>"Unknown error occured"
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
