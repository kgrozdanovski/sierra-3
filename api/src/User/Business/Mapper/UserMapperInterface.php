<?php

namespace App\User\Business\Mapper;

use App\User\Business\ValueObject\UserVO;
use App\User\Persistence\Doctrine\Entity\User;

interface UserMapperInterface
{
    /**
     * @param UserVO $userVO
     * @param User $user
     *
     * @return User
     */
    public function mapUserVOToUser(UserVO $userVO, User $user): User;
}
