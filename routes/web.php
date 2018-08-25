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

Route::get('/', function () {
    return view('welcome');
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


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
