<?php

namespace App\User\Business\Mapper;

use App\User\Business\ValueObject\UserVO;
use App\User\Persistence\Doctrine\Entity\User;

class UserMapper implements UserMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function mapUserVOToUser(UserVO $userVO, User $user): User
    {
        $user->fromArray($userVO->toArray());

        return $user;
    }
}