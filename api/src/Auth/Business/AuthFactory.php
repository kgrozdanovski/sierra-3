<?php

namespace App\Auth\Business;

use App\Kernel\Persistence\Doctrine\Handler\TransactionHandlerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

class AuthFactory implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    /**
     * @return TransactionHandlerInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[SubscribedService]
    public function createTransactionHandler(): TransactionHandlerInterface
    {
        return $this->container->get(__METHOD__);
    }
}
