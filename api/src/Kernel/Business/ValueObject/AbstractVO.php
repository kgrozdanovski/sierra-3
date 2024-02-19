<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

use App\Kernel\Business\Security\UserAwareTrait;
use App\Kernel\Business\Security\UserBuilderAwareInterface;
use App\Kernel\Communication\HttpFoundation\RequestStackAwareInterface;
use App\Kernel\Communication\HttpFoundation\RequestStackAwareTrait;

abstract class AbstractVO
    implements ValueObjectInterface, RequestStackAwareInterface, UserBuilderAwareInterface
{
    use RequestStackAwareTrait;
    use UserAwareTrait;
}
