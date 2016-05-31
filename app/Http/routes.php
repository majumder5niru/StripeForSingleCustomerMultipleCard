<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/create_customer', 'CreditCardController@AddCustomer');
Route::get('/add_card', 'CreditCardController@AddNewCard');
Route::get('/pay_amount', 'CreditCardController@PayInvoice');
Route::get('/delete_card', 'CreditCardController@DeleteOneCard');
