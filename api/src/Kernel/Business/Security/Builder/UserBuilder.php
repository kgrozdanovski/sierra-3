<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\Security\Builder;

use App\User\Persistence\Doctrine\Entity\User;
use App\User\Persistence\Doctrine\Repository\UserRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

//use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

final class UserBuilder implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    /**
     * @return User|null
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function build(): ?User
    {
        if ('cli' === PHP_SAPI) {
            return $this->buildFromCliRequest();
        }

        return $this->buildFromHttpRequest();
    }

    /**
     * @return User|null
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function buildFromCliRequest(): ?User
    {
        return $this->createUserRepository()->find(
            getenv('APP_SYSTEM_USER')
        );
    }

    /**
     * @return User|null
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function buildFromHttpRequest(): ?User
    {
        // todo: implement this method
//        $authContext = self::getAuthContext($this->createRequestStack()->getCurrentRequest());
//
//        if ($authContext === null) {
//            return null;
//        }
//
//        return $this->createUserRepository()->find(
//            $authContext->getUser()->getID()
//        );

        return $this->buildFromCliRequest();
    }

    /**
     * @return UserRepository
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[SubscribedService]
    public function createUserRepository(): UserRepository
    {
        return $this->container->get(__METHOD__);
    }
}
