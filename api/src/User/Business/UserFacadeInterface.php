<?php

namespace App\User\Business;

use App\Kernel\Business\ValueObject\CollectionResponseVO;
use App\Kernel\Business\ValueObject\CollectionVO;
use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\Kernel\Business\ValueObject\ResourceVO;
use App\User\Business\ValueObject\UserVO;

interface UserFacadeInterface
{
    /**
     * Specification:
     * - Creates the described User resource.
     * - Creates relationships with Role entities.
     * - Creates relationships with other Users.
     *
     * @param UserVO $userVO
     *
     * @return ResourceResponseVO
     * @api
     */
    public function createResource(UserVO $userVO): ResourceResponseVO;

    /**
     * Specification:
     * - Returns the requested User resource.
     *
     * @param ResourceVO $resourceVO
     *
     * @return ResourceResponseVO
     * @api
     *
     */
    public function readResource(ResourceVO $resourceVO): ResourceResponseVO;

    /**
     * Specification:
     * - Returns a filtered User collection.
     *
     * @param CollectionVO $collectionVO
     *
     * @return CollectionResponseVO
     * @api
     *
     */
    public function readCollection(CollectionVO $collectionVO): CollectionResponseVO;

    /**
     * Specification:
     * - Updates the designated User resource.
     * - Updates any relations to other entities.
     * - Updates any relations with other Users.
     *
     * @param UserVO $userVO
     *
     * @return ResourceResponseVO
     * @api
     */
    public function updateResource(UserVO $userVO): ResourceResponseVO;

    /**
     * Specification:
     * - Deletes the requested User resource.
     * - Deletes any relations to other entities.
     *
     * @param UserVO $userVO
     *
     * @return ResourceResponseVO
     * @api
     */
    public function deleteResource(UserVO $userVO): ResourceResponseVO;
}
