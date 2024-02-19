<?php

namespace App\Kernel\Business\ValueObjectResolver;

use App\Kernel\Business\DependencyInjection\ServiceResolverInterface;
use App\Kernel\Business\ValueObject\ValueObjectInterface;
use App\Kernel\Communication\HttpFoundation\Request\Builder\SortingBuilder;
use App\Kernel\Communication\HttpFoundation\Request\SortingBuilderAwareInterface;

final class SortingBuilderResolver implements ServiceResolverInterface
{
    public function __construct(
        private readonly SortingBuilder $sortingBuilder
    ) {}

    /**
     * {@inheritDoc}
     */
    public function supports(ValueObjectInterface $valueObject): bool
    {
        return $valueObject instanceof SortingBuilderAwareInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(ValueObjectInterface $valueObject): void
    {
        $valueObject->setSortingBuilder($this->sortingBuilder);
    }
}
