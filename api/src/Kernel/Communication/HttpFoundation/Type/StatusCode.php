<?php

namespace App\Kernel\Communication\HttpFoundation\Type;

enum StatusCode: int {
    case OK = 200;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case PRECONDITION_FAILED = 412;
    case VALIDATION_FAILED = 422;
    case INTERNAL_ERROR = 500;
    case SERVICE_UNAVAILABLE = 503;
}