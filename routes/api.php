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

Route::prefix('v1')->group(function(){

	Route::post('login', 'Api\AuthController@login');
	Route::post('register', 'Api\AuthController@register');

	Route::get('test_push_notif/{deviceToken}', 'Api\TestPushNotifController@testPush');

	Route::group(['middleware' => 'auth:api'], function(){

	 	Route::get('getUser', 'Api\AuthController@getUser');

	 	//Pasient
	 	Route::prefix('patient')->group(function(){
	 		Route::get('my_data', 'Api\PatientController@getMyData');
	 		Route::post('my_data', 'Api\PatientController@saveMyData');
	 	});
	 	
	});

});
