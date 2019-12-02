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
}
