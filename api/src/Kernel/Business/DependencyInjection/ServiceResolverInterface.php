<?php

namespace App\Kernel\Business\DependencyInjection;

use App\Kernel\Business\ValueObject\ValueObjectInterface;

interface ServiceResolverInterface
{
    /**
     * Whether this resolver can resolve the dependencies for the given ValueObject.
     *
     * @param ValueObjectInterface $valueObject
     *
     * @return bool
     */
    public function supports(ValueObjectInterface $valueObject): bool;

    /**
     * Injects the dependencies for the given ValueObject.
     *
     * @param ValueObjectInterface $valueObject
     *
     * @return void
     */
    public function resolve(ValueObjectInterface $valueObject): void;
}
