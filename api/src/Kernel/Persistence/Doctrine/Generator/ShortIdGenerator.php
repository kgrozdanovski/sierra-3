<?php

namespace App\Kernel\Persistence\Doctrine\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Symfony\Component\Uid\Uuid;

class ShortIdGenerator extends AbstractIdGenerator
{
    #[\Override]
    public function generateId(EntityManagerInterface $em, ?object $entity): mixed
    {
        $tableName = $em->getClassMetadata(get_class($entity))->getTableName();

        // Execute the function to generate the short ID
        return $em->getConnection()->fetchOne("SELECT fn_generate_short_id('$tableName')");
    }
}
