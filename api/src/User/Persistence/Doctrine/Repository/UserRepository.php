<?php

namespace App\User\Persistence\Doctrine\Repository;

use App\Kernel\Business\ValueObject\Pagination;
use App\Kernel\Business\ValueObject\Sorting;
use App\Kernel\Persistence\Doctrine\Repository\AbstractEntityRepository;
use App\User\Persistence\Doctrine\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends AbstractEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends AbstractEntityRepository implements PasswordUpgraderInterface
{
    protected const TABLE_ALIAS = 'u';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
     * @param array $filters
     * @param Pagination $pagination
     * @param Sorting $sorting
     *
     * @return Paginator
     */
    public function getCollection(array $filters, Pagination $pagination, Sorting $sorting): Paginator
    {
        $queryBuilder = $this->createQueryBuilder(self::TABLE_ALIAS);

        $this->compileQueryBuilderWhereClause($queryBuilder, $filters);
        $this->compileQueryBuilderOrderClause($queryBuilder, $sorting);

        $queryBuilder
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getLimit());

        return new Paginator($queryBuilder);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filters
     *
     * @return void
     */
    protected function compileQueryBuilderWhereClause(QueryBuilder $queryBuilder, array $filters): void
    {
        if (isset($filters['email'])) {
            $queryBuilder->andWhere(self::TABLE_ALIAS.'.email LIKE :email');
            $queryBuilder->setParameter('email', '%' . $filters['email'] . '%');
        }

        $this->applyBlameAuditFilters($queryBuilder, $filters);
        $this->applyTimestampAuditFilters($queryBuilder, $filters);
    }
}
