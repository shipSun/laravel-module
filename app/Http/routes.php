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
Route::group(['middleware'=>'sign'], function(){
	Route::get('gateway', 'GatewayController@index');
	Route::post('gateway', 'GatewayController@index');
});

Route::get('/', function () {
    return view('welcome');
});
