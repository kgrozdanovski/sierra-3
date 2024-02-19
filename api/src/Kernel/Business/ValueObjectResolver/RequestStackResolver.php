<?php

namespace App\Kernel\Business\ValueObjectResolver;

use App\Kernel\Business\DependencyInjection\ServiceResolverInterface;
use App\Kernel\Business\ValueObject\ValueObjectInterface;
use App\Kernel\Communication\HttpFoundation\RequestStackAwareInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestStackResolver implements ServiceResolverInterface
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {}

    /**
     * {@inheritDoc}
     */
    public function supports(ValueObjectInterface $valueObject): bool
    {
        return $valueObject instanceof RequestStackAwareInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(ValueObjectInterface $valueObject): void
    {
        $valueObject->setRequestStack($this->requestStack);
    }
}
