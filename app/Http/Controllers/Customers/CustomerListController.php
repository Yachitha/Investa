<?php
/**
 * Created by PhpStorm.
 * User: yachitha
 * Date: 2/3/19
 * Time: 10:10 PM
 */

namespace App\Http\Controllers\Customers;



use Illuminate\Support\Facades\DB;

class CustomerListController
{
    protected $customerController;

    public function __construct(CustomerController $controller)
    {
        $this->customerController = $controller;
    }

    public function getCustomerList() {
        $customers=[];
        $routes = DB::table('route')->get();
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
        return response()->json([
            'error' => false,
            'customers' => $customers,
            'routes' => $routes
        ]);
    }
}
