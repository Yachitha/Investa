<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('Home.home');
});
Route::get('/home1', function () {
    return view('home1');
});
Route::get('/createCustomer', function () {
    return view('Customer/createCustomer');
});
Route::get('/createLoan', function () {
    return view('Loans/createLoan');
});
Route::get('/dailyCollection', function () {
    return view('daywork/dailyCollection');
});
Route::get('/bankBalance', function () {
    return view('daywork/bankBalance');
});
Route::get('/cashBalance', function () {
    return view('daywork/cashBalance');
});
Route::get('/dashboard', function () {
    return view('Dashboard/mainDashboard');
});
Route::get('/calendar', function () {
    return view('Calendar/Calendar');
});
Route::get('/dailySummary', function () {
    return view('Summary/dailySummary');
});
Route::get('/daySheet', function () {
    return view('Reports.DaySheet.daySheet');
});
Route::get('/daySheetTest', function () {
    return view('Reports.Test.daySheetTest');
});
Route::get('/customerNumbers','CustomerController@getCustomers');

Route::get ('/getCalendarDates','CalendarController@getDates');

Route::post ('/deleteDate','CalendarController@deleteDate');

Route::get('/summary', 'DailySummaryController@fetch');

Route::post ('/createCustomer','CustomerController@createCustomer');

Route::post('/createLoan','LoanController@createLoan');

Route::get ('/createDaySheet','Reports\DaySheet\DaySheet@createPdf');

Route::post ('/getCustomersByRoute','Customers\CustomerController@getCustomersByRoute');

Route::get('/getCustomersByRoute', function () {
    return view('Test.customersByRoute');
});

Route::post ('/getCashBook','CashBookController@getCashBook');

Route::get('/getCashBook', function () {
    return view('Test.cashBook');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post ('/createCalendar', 'CalendarController@createCalendar')->name ('createCalendar');

Route::get('/testPdf', function () {
    return view('Reports.Test.testPdf');
});

Route::get ('/daySheetTestData','Reports\DaySheet\DaySheet@getDaySheetData');

Route::get('/getCustomerData', 'Customers\CustomerDashboardController@getCustomerData');

Route::post('/addCustomerToDb', 'Customers\CustomerDashboardController@addCustomer');

Route::get('/getCustomerList', 'Customers\CustomerListController@getCustomerList');

Route::post('/getLoanDetailsForCustomer','Loans\CustomerLoanController@getLoanDetailsForCustomer');

Route::get('/getLoanNumber','Loans\CustomerLoanController@getLoanNumber');

Route::get('{any}', function () {
    return view('Dashboard.mainDashboard');
})->where('any','.*');
