<?php

namespace App\User\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Kernel\Persistence\Doctrine\Entity\AbstractAudit;
use App\Kernel\Persistence\Doctrine\Entity\EntityInterface;

#[ORM\Table(name: 'role_audit')]
#[ORM\Entity]
class RoleAudit extends AbstractAudit
{
    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(name: 'entity_id')]
    protected EntityInterface $entity;
}
