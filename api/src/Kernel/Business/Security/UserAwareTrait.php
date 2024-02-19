<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\Security;

use App\Kernel\Business\Security\Builder\UserBuilder;
use App\User\Persistence\Doctrine\Entity\User;

trait UserAwareTrait
{
    private ?User $userContext = null;

    private readonly UserBuilder $userBuilder;

    /**
     * @return User|null
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function getUserContext(): ?User
    {
        if ($this->userContext === null) {
            $this->userContext = $this->buildUser();
        }

        return $this->userContext;
    }

    /**
     * @return User|null
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function buildUser(): ?User
    {
        return $this->getUserBuilder()->build();
    }

    /**
     * @return UserBuilder
     */
    public function getUserBuilder(): UserBuilder
    {
        return $this->userBuilder;
    }

    /**
     * @param UserBuilder $userBuilder
     *
     * @return void
     */
    public function setUserBuilder(UserBuilder $userBuilder): void
    {
        $this->userBuilder = $userBuilder;
    }
}
