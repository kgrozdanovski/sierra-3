<?php

namespace App\Kernel\Communication\HttpFoundation\Request;

use App\Kernel\Communication\HttpFoundation\Request\Builder\SortingBuilder;

interface SortingBuilderAwareInterface
{
    /**
     * @param SortingBuilder $sortingBuilder
     *
     * @return void
     */
    public function setSortingBuilder(SortingBuilder $sortingBuilder): void;
}
