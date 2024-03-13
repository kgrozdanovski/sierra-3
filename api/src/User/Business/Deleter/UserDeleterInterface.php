<?php

namespace App\User\Business\Deleter;

use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\User\Business\ValueObject\UserVO;

interface UserDeleterInterface
{
    /**
     * @param UserVO $userVO
     *
     * @return ResourceResponseVO
     */
    public function delete(UserVO $userVO): ResourceResponseVO;
}
