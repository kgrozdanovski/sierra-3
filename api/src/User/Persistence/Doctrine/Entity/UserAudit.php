<?php

namespace App\User\Persistence\Doctrine\Entity;

use App\Kernel\Persistence\Doctrine\Entity\AbstractAudit;
use App\Kernel\Persistence\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'user_audit')]
#[ORM\Entity]
class UserAudit extends AbstractAudit
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'entity_id')]
    protected EntityInterface $entity;
}
