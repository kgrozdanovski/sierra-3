<?php

namespace App\Auth\Persistence\Doctrine\Repository;

use App\Kernel\Business\ValueObject\Pagination;
use App\Kernel\Business\ValueObject\Sorting;
use App\Kernel\Persistence\Doctrine\Repository\AbstractEntityRepository;
use App\Auth\Persistence\Doctrine\Entity\AccessToken;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @extends AbstractEntityRepository<AccessToken>
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessToken[]    findAll()
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessTokenRepository extends AbstractEntityRepository
{
    protected const string TABLE_ALIAS = 'at';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    public function save(AccessToken $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function softDelete(AccessToken $entity, bool $flush = false): void
    {
        $entity->setDeleted(true);
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
