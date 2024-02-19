<?php

namespace App\User\Business\Writer;

use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\User\Business\ValueObject\UserVO;
use App\User\Persistence\Doctrine\Entity\User;

interface UserWriterInterface
{
    /**
     * @param UserVO $userVO
     * @param User $userContext
     *
     * @return ResourceResponseVO
     */
    public function create(UserVO $userVO, User $userContext): ResourceResponseVO;

    /**
     * @param UserVO $userVO
     * @param User $user
     *
     * @return ResourceResponseVO
     */
    public function update(UserVO $userVO, User $user): ResourceResponseVO;
}
