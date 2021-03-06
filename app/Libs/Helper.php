<?php

namespace App\Libs;

use App\Enums\NotificationTypeEnum;
use App\Enums\NotificationCategoryEnum;
use App\Enums\QueueEnum;
use App\Models\QueueProcess;

class Helper {

    /**
     * @param $status
     * @return bool
     */
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

    /**
     * @param $type
     * @param string $title
     * @return array
     */
    public static function setMessageNotification($type, $title = '', $category)
    {
        $message = [
            'aps' => [
                'alert' => [
                    'title' => $title,
                    'body' => '',
                ],
                'badge' => 0,
                'sound' => 'default',
                'content-available' => 1,
                'category' => NotificationCategoryEnum::update_queue
            ]
        ];

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
                    'category' => $category
                ]
            ];
        } else if ($type == NotificationTypeEnum::silent) {
            $message = [
                'aps' => [
                    'content-available' => 1,
                    'sound' => '',
                    'category' => $category
                ],
            ];
        }

        return $message;
    }

    /**
     * @param $doctor_schedule_id
     * @param $submit_time
     * @return mixed
     */
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
