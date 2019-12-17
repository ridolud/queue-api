<?php

namespace App\Http\Controllers\Api;

use App\Enums\QueueEnum;
use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Support\Facades\Log;

class TestPushNotifController extends Controller
{
    // TODO: REMOVE DEV STUFF
    public function testPush($deviceToken)
    {
        $push = new PushNotification('apn');

		$message = [
			'aps' => [
			    'content-available' => 1,
			    'sound' => '',
			    'category' => 'UPDATE_QUEUE'
			],
		];

		 $message = [
		 	'aps' => [
		 	    'alert' => [
		 	        'title' => 'Ini giliran kamu',
		 	        'body' => '',
                    ],
                'badge' => 0,
                'sound' => 'default',
                'content-available' => 1,
                'category' => 'UPDATE_QUEUE'
            ]
		 ];


		 try {
             $push->setMessage($message)
                 ->setDevicesToken([
                     $deviceToken,
                 ]);

             $push = $push->send();
             $response = $push->getFeedback();

             return response()->json([
                 "success" => true,
                 "message" => $response
             ], 200);

         } catch (\Error $error) {
            Log::error($error);
            return response()->json($error->getMessage(), ResponseCodeEnum::Error);
         }
    }
}
