<?php

namespace App\Libs;

use App\Enums\QueueEnum;

class Helper {

    public static function isQueueValid($status)
    {
        if ($status == QueueEnum::checkOut ||
            $status == QueueEnum::skipped ||
            $status == QueueEnum::terminate) {
            return false;
        }

        return true;
    }
}
