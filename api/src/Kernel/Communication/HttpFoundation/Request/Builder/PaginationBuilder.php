<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Communication\HttpFoundation\Request\Builder;

use App\Kernel\Business\ValueObject\Pagination;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class PaginationBuilder
{
    public function __construct(
        private RequestStack $requestStack
    ) {}

    /**
     * @return Pagination
     */
    public function build(): Pagination
    {
        $httpRequest = $this->requestStack->getCurrentRequest();

        $page = $httpRequest->query->getInt('page', Pagination::DEFAULT_PAGE);
        $limit = $httpRequest->query->getInt('limit', Pagination::DEFAULT_LIMIT);

        return (new Pagination())
            ->setPage($page)
            ->setLimit($limit);
    }
}
