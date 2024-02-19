<?php

namespace App\User\Business\Reader;

use App\Kernel\Business\ValueObject\CollectionResponseVO;
use App\Kernel\Business\ValueObject\CollectionVO;
use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\Kernel\Business\ValueObject\ResourceVO;

interface UserReaderInterface
{
    /**
     * @param ResourceVO $resourceVO
     *
     * @return ResourceResponseVO
     */
    public function read(ResourceVO $resourceVO): ResourceResponseVO;

    /**
     * @param CollectionVO $collectionVO
     *
     * @return CollectionResponseVO
     */
    public function readCollection(CollectionVO $collectionVO): CollectionResponseVO;
}