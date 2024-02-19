<?php

namespace App\Kernel\Business\ValueObjectResolver;

use App\Kernel\Business\DependencyInjection\ServiceResolverInterface;
use App\Kernel\Business\ValueObject\ValueObjectInterface;
use App\Kernel\Communication\HttpFoundation\Request\Builder\PaginationBuilder;
use App\Kernel\Communication\HttpFoundation\Request\PaginationBuilderAwareInterface;

final class PaginationBuilderResolver implements ServiceResolverInterface
{
    public function __construct(
        private readonly PaginationBuilder $paginationBuilder
    ) {}

    /**
     * {@inheritDoc}
     */
    public function supports(ValueObjectInterface $valueObject): bool
    {
        return $valueObject instanceof PaginationBuilderAwareInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(ValueObjectInterface $valueObject): void
    {
        $valueObject->setPaginationBuilder($this->paginationBuilder);
    }
}
