<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Communication\HttpFoundation\Request;

use App\Kernel\Business\ValueObject\Pagination;
use App\Kernel\Communication\HttpFoundation\Request\Builder\PaginationBuilder;

trait PaginationAwareTrait
{
    private ?Pagination $pagination = null;

    private readonly PaginationBuilder $paginationBuilder;

    /**
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        if ($this->pagination === null) {
            $this->pagination = $this->buildPagination();
        }

        return $this->pagination;
    }

    /**
     * @param Pagination $pagination
     *
     * @return $this
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * @return Pagination
     */
    protected function buildPagination(): Pagination
    {
        return $this->getPaginationBuilder()->build();
    }

    /**
     * @return PaginationBuilder
     */
    protected function getPaginationBuilder(): PaginationBuilder
    {
        return $this->paginationBuilder;
    }

    /**
     * @param PaginationBuilder $paginationBuilder
     *
     * @return void
     */
    public function setPaginationBuilder(PaginationBuilder $paginationBuilder): void
    {
        $this->paginationBuilder = $paginationBuilder;
    }
}
