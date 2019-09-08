<?php

use Illuminate\Http\Request;

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

Route::get('/getfixedtemplate', 'WxappletSourceController@geiIndexfixedtemplate');
Route::get('/getsigntemplate', 'WxappletSourceController@getSignTemplate');
Route::get('/getopenid', 'WxappletSourceController@getOpenid');
Route::get('/getphonenumber', 'WxappletSourceController@getPhoneNumber');
Route::post('/phonecomplate', 'PhoneController@phoneComplate');
Route::post('sendmessage', 'WxappletSourceController@sendMessage');



