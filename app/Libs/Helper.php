<?php

namespace App\Libs;

use App\Enums\NotificationTypeEnum;
use App\Enums\QueueEnum;
use App\Models\QueueProcess;

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

    public static function getQueueRemaining($doctor_schedule_id, $submit_time)
    {
        $queue_count = QueueProcess::where('doctor_schedule_id', $doctor_schedule_id)
            ->where('is_valid', QueueEnum::Valid)
            ->where('process_status', QueueEnum::waiting)
            ->where('submit_time', '<', $submit_time)
            ->get()
            ->count();

        return $queue_count;
    }
}
