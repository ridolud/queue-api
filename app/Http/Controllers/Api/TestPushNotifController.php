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
	    $message = [
	        'aps' => [
	            'alert' => [
	                'title' => '1 Notification test',
	                'body' => 'Just for testing purposes',
                    'queue_status' => QueueEnum::waiting,
                    'silent' => true
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
