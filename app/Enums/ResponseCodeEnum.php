<?php

namespace App\Enums;

/**
 * Class ResponseCodeEnum
 * @package App\Enums
 */
final class ResponseCodeEnum {
    const Success = 200;
    const Error = 500;
    const UnAuthorized = 401;
    const NotFound = 404;
    const Redirect = 301;
    const InvalidRequest = 406;
}
