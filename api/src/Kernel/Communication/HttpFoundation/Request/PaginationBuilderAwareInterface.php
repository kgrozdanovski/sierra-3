<?php

namespace App\Kernel\Communication\HttpFoundation\Request;

use App\Kernel\Communication\HttpFoundation\Request\Builder\PaginationBuilder;

interface PaginationBuilderAwareInterface
{
    /**
     * @param PaginationBuilder $paginationBuilder
     *
     * @return void
     */
    public function setPaginationBuilder(PaginationBuilder $paginationBuilder): void;
}
