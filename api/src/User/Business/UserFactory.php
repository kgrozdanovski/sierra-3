<?php

namespace App\User\Business;

use App\Kernel\Persistence\Doctrine\Handler\TransactionHandlerInterface;
use App\User\Business\Reader\UserReaderInterface;
use App\User\Business\Writer\UserWriterInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

class UserFactory implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    /**
     * @return UserReaderInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[SubscribedService]
    public function createUserReader(): UserReaderInterface
    {
        return $this->container->get(__METHOD__);
    }

    /**
     * @return UserWriterInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[SubscribedService]
    public function createUserWriter(): UserWriterInterface
    {
        return $this->container->get(__METHOD__);
    }

    /**
     * @return TransactionHandlerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[SubscribedService]
    public function createTransactionHandler(): TransactionHandlerInterface
    {
        return $this->container->get(__METHOD__);
    }
}
