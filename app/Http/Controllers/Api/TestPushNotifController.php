<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Edujugon\PushNotification\PushNotification;

class TestPushNotifController extends Controller
{
    public function testPush($deviceToken)
    {
    	$push = new PushNotification('apn');
	    $message = [
	        'aps' => [
	            'alert' => [
	                'title' => '1 Notification test',
	                'body' => 'Just for testing purposes'
	            ],
	            'sound' => 'default'
	        ]
	    ];
	    $push->setMessage($message)
	        ->setDevicesToken([
	            $deviceToken,  
	        ]);
	    $push = $push->send();
		$response = $push->getFeedback(); 
    	return response()->json($response, 200);
    }
}
