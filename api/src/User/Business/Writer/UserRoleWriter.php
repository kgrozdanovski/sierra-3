<?php

namespace App\User\Business\Writer;

use App\Kernel\Business\ValueObject\CollectionResponseVO;
use App\Kernel\Communication\HttpKernel\Exception\BadRequestHttpException;
use App\User\Business\Builder\UserRoleBuilder;
use App\User\Business\ValueObject\UserRoleVO;
use App\User\Persistence\Doctrine\Entity\User;
use App\User\Persistence\Doctrine\Repository\RoleRepository;
use App\User\Persistence\Doctrine\Repository\UserRoleRepository;

class UserRoleWriter implements UserRoleWriterInterface
{
    public function __construct(
        private readonly UserRoleBuilder $userRoleBuilder,
        private readonly UserRoleRepository $userRoleRepository,
        private readonly RoleRepository $roleRepository
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function create(?UserRoleVO $userRoleVO, User $user): CollectionResponseVO
    {
        // By default, every user must have at least one user role
        if ($userRoleVO !== null) {
            $roles = $userRoleVO->getRoles();
        } else {
            // todo: use configurable default role id
            $defaultRole = $this->roleRepository->findOneBy(['name' => 'default']);
            $roles = [$defaultRole->getId()];
        }

        $userRoles = $this->userRoleBuilder->buildCollection($roles, $user);

        $this->userRoleRepository->addCollection($userRoles);

        return (new CollectionResponseVO())
            ->setData($userRoles);
    }

    /**
     * {@inheritDoc}
     */
    public function update(UserRoleVO $userRoleVO, User $user): CollectionResponseVO
    {
        $userRoles = $this->userRoleBuilder->buildCollection($userRoleVO->getRoles(), $user);

        $loggedInUser = $userRoleVO->getUserContext();

        // Users may only assign roles with a lower or equal weight than their own top role
        // todo ...

        // Remove roles that are not in the new collection
        $this->userRoleRepository->removeCollection(
            $user->getUserRoles()->diff($userRoles),
            true
        );

        // Add roles that are not in the existing collection
        $this->userRoleRepository->addCollection(
            $userRoles->diff($user->getUserRoles())
        );

        return (new CollectionResponseVO())
            ->setData($userRoles);
    }
}
