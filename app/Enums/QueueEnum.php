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

    const checkOut = 0;
    const checkIn = 1;
    const skipped = 2;
}
