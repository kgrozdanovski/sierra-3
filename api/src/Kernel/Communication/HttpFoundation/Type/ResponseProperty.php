<?php

namespace App\Kernel\Communication\HttpFoundation\Type;

enum ResponseProperty: string {
    case MESSAGE = 'message';
    case DATA = 'data';
}