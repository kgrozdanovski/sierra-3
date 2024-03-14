<?php

namespace App\Auth\Business;

use App\Kernel\Business\ValueObject\CollectionResponseVO;
use App\Kernel\Business\ValueObject\CollectionVO;
use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\Kernel\Business\ValueObject\ResourceVO;
use App\User\Business\ValueObject\UserVO;

class AuthFacade implements AuthFacadeInterface
{
    public function __construct(
        private readonly AuthFactory $factory
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function createResource(UserVO $userVO): ResourceResponseVO
    {
        return $this->factory->createTransactionHandler()->handleTransaction(function () use ($userVO) {
            $userResponseVO = $this->factory
                ->createUserWriter()
                ->create($userVO, $userVO->getUserContext());

            $this->factory
                ->createUserRoleWriter()
                ->create($userVO->getUserRoleVO(), $userResponseVO->getData());

            return $userResponseVO
                ->setSerializerGroups([
                    'user_resource',
                    'user_relation',
                    'metadata'
                ]);
        });
    }
}
