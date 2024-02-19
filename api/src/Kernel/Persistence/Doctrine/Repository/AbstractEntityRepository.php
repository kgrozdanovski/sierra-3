<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Persistence\Doctrine\Repository;

use App\Kernel\Business\ValueObject\Sorting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractEntityRepository extends ServiceEntityRepository
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filters
     *
     * @return void
     */
    protected function compileQueryBuilderWhereClause(QueryBuilder $queryBuilder, array $filters): void
    {
        $this->applyEntityAuditFilters($queryBuilder, $filters);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Sorting $sorting
     *
     * @return void
     */
    protected function compileQueryBuilderOrderClause(QueryBuilder $queryBuilder, Sorting $sorting): void
    {
        $orderBy = new Expr\OrderBy();

        $sortingCollection = $sorting->getCollectionByEntityName($this->getEntityName());

        foreach ($sortingCollection as $sortBy => $sortDir) {
            $orderBy->add(static::TABLE_ALIAS . '.' . $sortBy, $sortDir);
        }

        $queryBuilder->orderBy($orderBy);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filters
     *
     * @return void
     */
    protected function applyEntityAuditFilters(QueryBuilder $queryBuilder, array $filters): void
    {
        $this->applyBlameAuditFilters($queryBuilder, $filters);
        $this->applyTimestampAuditFilters($queryBuilder, $filters);
        $this->applySoftDeleteAuditFilters($queryBuilder, $filters);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filters
     *
     * @return void
     */
    protected function applyBlameAuditFilters(QueryBuilder $queryBuilder, array $filters): void
    {
        if (isset($filters['created_by_id'])) {
            $queryBuilder->andWhere(static::TABLE_ALIAS.'.createdBy = :created_by_id');
            $queryBuilder->setParameter('created_by_id', $filters['created_by_id']);
        }
        if (isset($filters['modified_by_id'])) {
            $queryBuilder->andWhere(static::TABLE_ALIAS.'.modifiedBy = :modified_by_id');
            $queryBuilder->setParameter('modified_by_id', $filters['modified_by_id']);
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filters
     *
     * @return void
     */
    protected function applyTimestampAuditFilters(QueryBuilder $queryBuilder, array $filters): void
    {
        if (isset($filters['date_created_from'])) {
            $queryBuilder->andWhere(static::TABLE_ALIAS.'.createdAt >= :date_created_from');
            $queryBuilder->setParameter('date_created_from', $filters['date_created_from']);
        }
        if (isset($filters['date_created_to'])) {
            $queryBuilder->andWhere(static::TABLE_ALIAS.'.createdAt < :date_created_to');
            $queryBuilder->setParameter('date_created_to', date('Y-m-d', strtotime($filters['date_created_to'] . ' + 1 days')));
        }
        if (isset($filters['date_modified_from'])) {
            $queryBuilder->andWhere(static::TABLE_ALIAS.'.modifiedAt >= :date_modified_from');
            $queryBuilder->setParameter('date_modified_from', $filters['date_modified_from']);
        }
        if (isset($filters['date_modified_to'])) {
            $queryBuilder->andWhere(static::TABLE_ALIAS.'.modifiedAt < :date_modified_to');
            $queryBuilder->setParameter('date_modified_to', date('Y-m-d', strtotime($filters['date_modified_to'] . ' + 1 days')));
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filters
     *
     * @return void
     */
    protected function applySoftDeleteAuditFilters(QueryBuilder $queryBuilder, array $filters): void
    {
        $queryBuilder->andWhere(static::TABLE_ALIAS.'.deleted = :deleted');

        isset($filters['deleted'])
            ? $queryBuilder->setParameter('deleted', $filters['deleted'])
            : $queryBuilder->setParameter('deleted', 0);
    }
}
