<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Communication\HttpFoundation;

use Symfony\Component\HttpFoundation\RequestStack;

interface RequestStackAwareInterface
{
    /**
     * @return RequestStack
     */
    public function getRequestStack(): RequestStack;

    /**
     * @param RequestStack $requestStack
     *
     * @return $this
     */
    public function setRequestStack(RequestStack $requestStack);
}
