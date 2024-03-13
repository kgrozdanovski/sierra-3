<?php

namespace App\User\Persistence\Doctrine\Repository;

use App\Kernel\Persistence\Doctrine\Repository\AbstractEntityRepository;
use App\User\Persistence\Doctrine\Collection\UserRoleCollection;
use App\User\Persistence\Doctrine\Entity\UserRole;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractEntityRepository<UserRole>
 *
 * @method UserRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRole[]    findAll()
 * @method UserRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRoleRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRole::class);
    }

    /**
     * @param UserRole $entity
     * @param bool $flush
     *
     * @return void
     */
    public function add(UserRole $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param UserRoleCollection $collection
     * @param bool $flush
     *
     * @return void
     */
    public function addCollection(UserRoleCollection $collection, bool $flush = false): void
    {
        foreach ($collection as $item) {
            $this->getEntityManager()->persist($item);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param UserRole $entity
     * @param bool $flush
     *
     * @return void
     */
    public function softRemove(UserRole $entity, bool $flush = false): void
    {
        $entity->setDeleted(true);
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param UserRoleCollection $collection
     * @param bool $flush
     *
     * @return void
     */
    public function softRemoveCollection(UserRoleCollection $collection, bool $flush = false): void
    {
        foreach ($collection as $item) {
            $this->softRemove($item);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
