<?php

namespace App\User\Business\Reader;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use App\User\Persistence\Doctrine\Repository\UserRepository;
use App\Kernel\Business\ValueObject\CollectionResponseVO;
use App\Kernel\Business\ValueObject\CollectionVO;
use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\Kernel\Business\ValueObject\ResourceVO;

readonly class UserReader implements UserReaderInterface
{
    public function __construct(private UserRepository $userRepository) {}

    /**
     * {@inheritDoc}
     */
    public function read(ResourceVO $resourceVO): ResourceResponseVO
    {
        $customer = $this->userRepository->find(
            $resourceVO->getId()
        );

        if ($customer === null) {
            throw new ResourceNotFoundException('Address resource with ID '.$resourceVO->getId().' was not found.');
        }

        return (new ResourceResponseVO())
            ->setData($customer)
            ->setSerializerGroups([
                'user_resource',
                'metadata'
            ]);
    }

    /**
     * {@inheritDoc}
     */
    public function readCollection(CollectionVO $collectionVO): CollectionResponseVO
    {
        $paginator = $this->userRepository->getCollection(
            $collectionVO->getFilters(),
            $collectionVO->getPagination(),
            $collectionVO->getSorting()
        );

        return (new CollectionResponseVO())
            ->setData($paginator)
            ->setSerializerGroups([
                'user_collection',
                'metadata'
            ])
            ->setPagination($collectionVO->getPagination())
            ->setSorting($collectionVO->getSorting());
    }
}