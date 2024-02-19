<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Communication\HttpFoundation\Request\Builder;

use App\Kernel\Business\ValueObject\Sorting;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class SortingBuilder
{
    public function __construct(
        private RequestStack $requestStack
    ) {}

    /**
     * @return Sorting
     */
    public function build(): Sorting
    {
        $httpRequest = $this->requestStack->getCurrentRequest();

        $sortBy = $httpRequest->query->all('sort_by');
        if(empty($soryBy)) {
            $sortBy = Sorting::DEFAULT_SORT_BY;
        }

        $sortDir = $httpRequest->query->all('sort_dir');
        if(empty($sortDir)) {
            $sortDir = Sorting::DEFAULT_SORT_DIR;
        }

        return (new Sorting())
            ->setSortBy($sortBy)
            ->setSortDir($sortDir);
    }
}
