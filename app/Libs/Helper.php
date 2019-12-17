<?php

namespace App\Libs;

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
}
