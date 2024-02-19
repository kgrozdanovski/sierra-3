<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

use App\Kernel\Communication\HttpFoundation\Request\CriteriaAwareTrait;
use App\Kernel\Communication\HttpFoundation\Request\PaginationAwareTrait;
use App\Kernel\Communication\HttpFoundation\Request\PaginationBuilderAwareInterface;
use App\Kernel\Communication\HttpFoundation\Request\SortingAwareTrait;
use App\Kernel\Communication\HttpFoundation\Request\SortingBuilderAwareInterface;

class CollectionVO extends AbstractVO implements PaginationBuilderAwareInterface, SortingBuilderAwareInterface
{
    use CriteriaAwareTrait;
    use PaginationAwareTrait;
    use SortingAwareTrait;
}
