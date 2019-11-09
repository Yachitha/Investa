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

Route::get('/getCustomersToDisable', 'Customers\CustomerDashboardController@getCustomersToDisable');

Route::post('/addCustomerToDb', 'Customers\CustomerDashboardController@addCustomer');

Route::get('/getCustomerNumbers', 'Customers\CustomerDashboardController@getCustomerNumbers');

Route::post('/disableCustomers','Customers\CustomerDashboardController@disableCustomers');

Route::post('/editCustomer', 'Customers\CustomerDashboardController@editCustomer');

Route::post('/deleteCustomer', 'Customers\CustomerDashboardController@deleteCustomer');

Route::get('/getCustomerList', 'Customers\CustomerListController@getCustomerList');

Route::post('/getLoanDetailsForCustomer','Loans\CustomerLoanController@getLoanDetailsForCustomer');

Route::get('/getLoanNumber','Loans\CustomerLoanController@getLoanNumber');

Route::post('/calculateEndDate','Loans\CustomerLoanController@calculateEndDate');

Route::post('/getTotalAndInstallment','Loans\CustomerLoanController@getBasicLoanDetails');

Route::post('/addCustomerLoan','Loans\CustomerLoanController@addCustomerLoan');

Route::post('/editCustomerLoan','Loans\CustomerLoanController@editCustomerLoan');

Route::post('/deleteCustomerLoan','Loans\CustomerLoanController@deleteCustomerLoan');

Route::post('/clearCustomerLoan','Loans\CustomerLoanController@clearCustomerLoan');

Route::post('/getCashBookRecords','CashBook\CashBookController@getCashBookRecords');

Route::post('/getBankBookRecords','BankBook\BankBookController@getBankBookRecords');

Route::post('/getExpenseData','Expenses\ExpenseController@getExpenseData');

Route::post('/getExpenseDataByRoute','Expenses\ExpenseController@getExpenseDataByRoute');

Route::get('/getOtherExpenseNumber','Expenses\ExpenseController@getOtherExpenseNumber');

Route::get('/getSalesRepExpenseNumber','Expenses\ExpenseController@getSalesRepExpenseNumber');

Route::post('/addSalesRepExpense','Expenses\ExpenseController@addSalesRepExpense');

Route::post('/editSalesRepExpense','Expenses\ExpenseController@editSalesRepExpense');

Route::post('/addOtherExpense','Expenses\ExpenseController@addOtherExpense');

Route::post('/editOtherExpense','Expenses\ExpenseController@editOtherExpense');

Route::post('/deleteOtherExpense','Expenses\ExpenseController@deleteOtherExpense');

Route::post('/deleteSalesRepExpense','Expenses\ExpenseController@deleteSalesRepExpense');

Route::get('/getSettingsData','Settings\SettingsController@getSettingsData');

Route::get('/getSalesRepNumber','Settings\SettingsController@getSalesRepNumber');

Route::post('/addNewSalesRep','Settings\SettingsController@addNewSalesRep');

Route::post('/editSalesRep','Settings\SettingsController@editSalesRep');

Route::post('/deleteSalesRep','Settings\SettingsController@deleteSalesRep');

Route::get('/getRouteNumber','Settings\SettingsController@getRouteNumber');

Route::post('/deleteRoute','Settings\SettingsController@deleteRoute');

Route::post('/editRoute','Settings\SettingsController@editRoute');

Route::post('/addNewRoute','Settings\SettingsController@addNewRoute');

Route::post('/cashBookAmountUpdate','Settings\SettingsController@cashBookAmountUpdate');

Route::get('/getInitialDataDSummary','dailySummary\DailySummaryController@getInitialData');

Route::get('/getInitialDataDaySheet','Reports\DaySheet\DaySheet@getInitialData');

Route::post('/daySheetDataByDate','Reports\DaySheet\DaySheet@getDataByDate');

Route::get('/getInitialDataPaySheet','Reports\PaySheet\PaySheetController@getInitialData');

Route::post('/daySheetSaveReq', 'Reports\DaySheet\DaySheet@storeDaySheetData');

Route::post('/getPayData','Reports\PaySheet\PaySheetController@getPayDataForSalesRep');

Route::post('/addSalaryPayment','Reports\PaySheet\PaySheetController@addSalaryPayment');

Route::get('{any}', function () {
    return view('Dashboard.mainDashboard');
})->where('any','.*');
