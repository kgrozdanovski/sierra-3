<?php

namespace App\User\Business\Deleter;

use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\User\Business\ValueObject\UserVO;
use App\User\Persistence\Doctrine\Collection\UserRoleCollection;
use App\User\Persistence\Doctrine\Repository\UserRepository;
use App\User\Persistence\Doctrine\Repository\UserRoleRepository;
use App\User\Business\Deleter\UserDeleterInterface;

readonly class UserDeleter implements UserDeleterInterface
{
    public function __construct(
        private UserRoleRepository $userRoleRepository,
        private UserRepository $userRepository
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function delete(UserVO $userVO): ResourceResponseVO
    {
        $user = $this->userRepository->find($userVO->getId());

        $userRoles = $this->userRoleRepository->findBy(['user' => $user]);
        $this->userRoleRepository->softRemoveCollection(new UserRoleCollection($userRoles));

        $this->userRepository->softRemove($user);

        return (new ResourceResponseVO())
            ->setData($user);
    }
}
