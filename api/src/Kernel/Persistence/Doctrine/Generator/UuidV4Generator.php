<?php

namespace App\Kernel\Persistence\Doctrine\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Symfony\Component\Uid\Uuid;

class UuidV4Generator extends AbstractIdGenerator
{
    #[\Override]
    public function generateId(EntityManagerInterface $em, ?object $entity): mixed
    {
        return Uuid::v4();
    }
}
