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

	Route::post('login', 'Api\AuthController@login')->name("login");
	Route::post('register', 'Api\AuthController@register');

	Route::get('test_push_notif/{deviceToken}', 'Api\TestPushNotifController@testPush');

	/* Hospital Routing */
	Route::name('hospital.index')->get('hospital', 'Api\HospitalController@index');
	Route::name('hospital.search')->get('hospital/{full_name}', 'Api\HospitalController@search');
	/* End Hospital Routing*/

    /* Poli Clinic Routing */
    Route::name('poli.index')->get('poli/{hospital_id}', 'Api\PoliClinicController@index');
    Route::name('poli.search')->get('poli/{hospital_id}/{poli_name}', 'Api\PoliClinicController@search');
    /* End Poli Clinic Routing */

    /* Doctor Routing */
    Route::name('doctor.index')->get('doctor/{hospital_id}/{poli_id}', 'Api\DoctorController@index');
    Route::name('doctor.search')->get('doctor/{hospital_id}/{poli_id}/{doctor_name}', 'Api\DoctorController@search');
    /* End Doctor Routing */

	Route::group(['middleware' => 'auth:api'], function(){

	 	Route::get('user', 'Api\AuthController@getUser');

	 	//Add device token
	 	Route::post('add-device-token', 'Api\AuthController@addDeviceToken');

	 	//Pasient
	 	Route::prefix('patient')->group(function(){
	 		Route::get('my_data', 'Api\PatientController@getMyData');
	 		Route::post('my_data', 'Api\PatientController@saveMyData');
	 	});

	});

});
