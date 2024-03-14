<?php

namespace App\Auth\Business;

use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\User\Business\ValueObject\UserVO;

interface AuthFacadeInterface
{
    /**
     *
     * @param UserVO $userVO
     *
     * @return ResourceResponseVO
     * @api
     */
    public function createResource(UserVO $userVO): ResourceResponseVO;
}
