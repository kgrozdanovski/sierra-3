<?php

namespace App\User\Business;

//use App\User\Business\ValueObject\UserVO;
use App\Kernel\Business\ValueObject\CollectionResponseVO;
use App\Kernel\Business\ValueObject\CollectionVO;
use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\Kernel\Business\ValueObject\ResourceVO;
use App\User\Business\ValueObject\UserVO;

class UserFacade implements UserFacadeInterface
{
    public function __construct(
        private readonly UserFactory $factory
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

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function readResource(ResourceVO $resourceVO): ResourceResponseVO
    {
        return $this->factory
            ->createUserReader()
            ->read($resourceVO);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function readCollection(CollectionVO $collectionVO): CollectionResponseVO
    {
        return $this->factory
            ->createUserReader()
            ->readCollection($collectionVO);
    }

//    /**
//     * {@inheritDoc}
//     *
//     * @api
//     */
//    public function updateResource(UserVO $userVO): ResourceResponseVO
//    {
//        return $this->factory->createTransactionHandler()->handleTransaction(function () use ($userVO) {
//            $userResponseVO = $this->factory
//                ->createUserReader()
//                ->read($userVO);
//
//            $this->factory
//                ->createUserWriter()
//                ->update($userVO, $userResponseVO->getData());
//
//            $this->factory
//                ->createUserPaymentWriter()
//                ->update($userResponseVO->getData());
//
//            return $userResponseVO;
//        });
//    }
}
