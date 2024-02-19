<?php

namespace App\Kernel\Symfony\Environment\Type;

enum Environment: string {
    case DEV = 'dev';
    case STAGE = 'stage';
    case PRODUCTION = 'prod';
}