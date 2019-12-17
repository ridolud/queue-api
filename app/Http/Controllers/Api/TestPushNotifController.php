<?php

namespace App\Http\Controllers\Api;

use App\Enums\QueueEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Edujugon\PushNotification\PushNotification;

class TestPushNotifController extends Controller
{
    // TODO: REMOVE DEV STUFF
    public function testPush($deviceToken)
    {
    	$push = new PushNotification('apn');

		// Silet Notif - jika admin hit ke antrian berikutnya
		$message = [
			'aps' => [
			    'content-available' => 1,
			    'sound' => '',
			    'category' => 'UPDATE_QUEUE'
			],
		];

		// ini giliran kamu
		// $message = [
		// 	'aps' => [
		// 	    'alert' => [
		// 	        'title' => 'Ini giliran kamu',
		// 	        'body' => '',
		// 	    ],
		// 	'badge' => 0,		
		// 	'sound' => 'default',
		// 	'content-available' => 1,
		// 	'category' => 'UPDATE_QUEUE'
		// ];

		// 1 orang lagi, abis itu giliran kamu
		// $message = [
		// 	'aps' => [
		// 	    'alert' => [
		// 	        'title' => '1 antrian lagi, giliran kamu',
		// 	        'body' => '',
		// 	    ],
		// 	'badge' => 0,		
		// 	'sound' => 'default',
		// 	'content-available' => 1,
		// 	'category' => 'UPDATE_QUEUE'
		// ];

		// 2 orang lagi, abis itu giliran kamu
		// $message = [
		// 	'aps' => [
		// 	    'alert' => [
		// 	        'title' => '2 antrian lagi, giliran kamu',
		// 	        'body' => '',
		// 	    ],
		// 	'badge' => 0,		
		// 	'sound' => 'default',
		// 	'content-available' => 1,
		// 	'category' => 'UPDATE_QUEUE'
		// ];

	    $push->setMessage($message)
	        ->setDevicesToken([
	            $deviceToken,
	        ]);
	    $push = $push->send();
		$response = $push->getFeedback();
    	return response()->json($response, 200);
    }
}
