<?php

namespace App\Kernel\Business\Security;

use App\Kernel\Business\Security\Builder\UserBuilder;

interface UserBuilderAwareInterface
{
    /**
     * @param UserBuilder $userBuilder
     *
     * @return void
     */
    public function setUserBuilder(UserBuilder $userBuilder): void;
}
