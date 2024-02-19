<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Communication\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Request;

trait CriteriaAwareTrait
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return array_merge(
            $this->getRequest()->query->all(),
            $this->getRequest()->attributes->get('_route_params', [])
        );
    }

    /**
     * @return Request
     */
    abstract public function getRequest(): Request;
}
