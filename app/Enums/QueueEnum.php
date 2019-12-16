<?php


namespace App\Enums;


/**
 * Class QueueEnum
 * @package App\Enums
 */
final class QueueEnum
{
    const Valid = 1;
    const inValid = 0;

    const waiting = 0;
    const checkIn = 1;
    const checkOut = 2;
    const skipped = 3;
    const terminate = 4;

    const currentQueueEmpty = "There is no active queue";
    const currentQueueExists = "You have an active queue";
    const successStoringQueue = "You have been queued";
    const failedStoringQueue = "You already in queue";
}
