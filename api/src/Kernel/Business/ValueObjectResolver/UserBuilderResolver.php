<?php

namespace App\Kernel\Business\ValueObjectResolver;

use App\Kernel\Business\DependencyInjection\ServiceResolverInterface;
use App\Kernel\Business\Security\Builder\UserBuilder;
use App\Kernel\Business\Security\UserBuilderAwareInterface;
use App\Kernel\Business\ValueObject\ValueObjectInterface;

final readonly class UserBuilderResolver implements ServiceResolverInterface
{
    public function __construct(
        private UserBuilder $userBuilder
    ) {}

    /**
     * {@inheritDoc}
     */
    public function supports(ValueObjectInterface $valueObject): bool
    {
        return $valueObject instanceof UserBuilderAwareInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(ValueObjectInterface $valueObject): void
    {
        $valueObject->setUserBuilder($this->userBuilder);
    }
}
