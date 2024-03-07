<?php

namespace App\User\Business\Writer;

use App\Kernel\Business\ValueObject\CollectionResponseVO;
use App\User\Business\ValueObject\UserRoleVO;
use App\User\Persistence\Doctrine\Entity\User;

interface UserRoleWriterInterface
{
    /**
     * @param UserRoleVO $userRoleVO
     * @param User $user
     *
     * @return CollectionResponseVO
     */
    public function create(UserRoleVO $userRoleVO, User $user): CollectionResponseVO;

    /**
     * @param UserRoleVO $userRoleVO
     * @param User $user
     *
     * @return CollectionResponseVO
     */
    public function update(UserRoleVO $userRoleVO, User $user): CollectionResponseVO;
}
