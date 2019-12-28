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

    /* Authentification */
	Route::name("login")->post('login', 'Api\Auth\LoginController@login');
	Route::name("register")->post('register', 'Api\Auth\RegisterController@register');
    Route::get('email/verify/{id}/{hash}', 'Api\Auth\VerificationController@verify')->name('verification.verify');
	/* end authentification */

	Route::get('test_push_notif/{deviceToken}', 'Api\TestPushNotifController@testPush');

	/* Hospital Routing */
	Route::name('hospital.index')->get('hospital', 'Api\HospitalController@index');
	Route::name('hospital.search')->get('hospital/{full_name}', 'Api\HospitalController@search');
	/* End Hospital Routing*/

    /* Poli Clinic Routing */
    Route::name('poli.index')->get('poli/{hospital_id}', 'Api\PoliClinicController@index');
    Route::name('poli.search')->get('poli/{hospital_id}/{poli_name}', 'Api\PoliClinicController@search');
    /* End Poli Clinic Routing */

    /* Insurance Routing */
    Route::name('insurance.index')->get('insurance/{hospital_id}', 'Api\InsuranceController@index');
    /* End Insurance Routing */

    /* Doctor Routing */
    Route::name('doctor.index')->get('doctor/{hospital_id}/{poli_id}', 'Api\DoctorController@index');
    Route::name('doctor.search')->get('doctor/{hospital_id}/{poli_id}/{doctor_name}', 'Api\DoctorController@search');
    /* End Doctor Routing */

    /* Admin RS Routing */
    Route::name('admin.queue.index')->get('admin/queue/index/{hospital_id}/{poli_id}', 'Api\Admin\QueueProcessController@getTodayQueue');
    Route::name('admin.queue.update-status')->post('admin/queue/update-status', 'Api\Admin\QueueProcessController@updateCurrentQueueStatus');
    Route::name('admin.queue.estimate')->get('admin/queue/calculate/{doctor_schedule_id}', 'Api\Admin\QueueProcessController@calculateEstimation');
    Route::name('admin.queue.notification')->get('admin/queue/notification/{queue_id}', 'Api\Admin\QueueProcessController@sendNotification');
    /* End Admin RS Routing*/

	Route::group(['middleware' => ['auth:api', 'verified']], function(){

	 	Route::get('user', 'Api\AuthController@getUser');

        /* Queue Routing */
        Route::name('queue.store')->post('queue', 'Api\QueueProcessController@store');
        Route::name('queue.index')->get('queue/index', 'Api\QueueProcessController@index');
        Route::name('queue.current')->get('queue/current', 'Api\QueueProcessController@getCurrentQueue');
        Route::name('queue.time.estimation')->get('queue/estimation', 'Api\QueueProcessController@getQueueEstimationTime');
        /* End Queue Routing */

	 	/* Add Device Token */
	 	Route::post('add-device-token', 'Api\AuthController@addDeviceToken');

	 	/* Patient Routing */
	 	Route::prefix('patient')->group(function(){
	 		Route::get('index', 'Api\PatientController@index');
	 		Route::post('store', 'Api\PatientController@store');
	 		Route::post('update/{patient_id}', 'Api\PatientController@update');
	 		Route::get('show', 'Api\PatientController@show');
	 	});
	 	/* End Patient Routing */

	});

});
