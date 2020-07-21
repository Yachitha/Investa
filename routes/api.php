<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'api\LoginController@login');
Route::post('/liteLogin', 'api\LoginController@liteLogin');
Route::group(['middleware'=>'api'],function (){
    Route::post('/users', 'api\UserController@store');
    Route::post('/customerRepayments','api\RepaymentController@customerRepayments');
    Route::post ('/editCustomerRepayments','api\RepaymentController@editCustomerRepayment');
    Route::post ('/createPocketMoney','api\PocketMoneyController@createPocketMoneyRow');
    Route::post('/addCustomerToDb', 'Customers\CustomerDashboardController@addCustomer');
    Route::post('/calculateEndDate','Loans\CustomerLoanController@calculateEndDate');
    Route::post('/addCustomerLoan','Loans\CustomerLoanController@addCustomerLoan');
    Route::post('/getTotalAndInstallment','Loans\CustomerLoanController@getBasicLoanDetails');
    Route::post('/editCustomerLoan','Loans\CustomerLoanController@editCustomerLoan');
    Route::post('/addOtherExpense','Expenses\ExpenseController@addOtherExpense');
    Route::post('/daySheetDataByDate','Reports\DaySheet\DaySheet@getDataByDate');
});
