<?php

namespace App\Libs;

use App\Enums\NotificationTypeEnum;
use App\Enums\QueueEnum;

class Helper {

    public static function isQueueValid($status)
    {
        $exit_status = [
            QueueEnum::checkOut,
            QueueEnum::skipped,
            QueueEnum::terminate
        ];

        if (in_array($status, $exit_status)) {
            return false;
        }

        return true;
    }

    public static function setMessageNotification($type, $title = '')
    {
        if ($type == NotificationTypeEnum::normal) {
            $message = [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => '',
                    ],
                    'badge' => 0,
                    'sound' => 'default',
                    'content-available' => 1,
                    'category' => 'UPDATE_QUEUE'
                ]
            ];
        } else if ($type == NotificationTypeEnum::silent) {
            $message = [
                'aps' => [
                    'content-available' => 1,
                    'sound' => '',
                    'category' => 'UPDATE_QUEUE'
                ],
            ];
        }

        return $message;
    }
}
