<?php

namespace App\User\Persistence\Doctrine\Entity;

use App\Kernel\Persistence\Doctrine\Entity\AbstractAudit;
use App\Kernel\Persistence\Doctrine\Entity\EntityInterface;
use App\Kernel\Persistence\Doctrine\Generator\UuidV4Generator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV4;

#[ORM\Table(name: 'user_audit')]
#[ORM\Entity]
class UserAudit extends AbstractAudit
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidV4Generator::class)]
    private ?UuidV4 $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'entity_id')]
    protected EntityInterface $entity;
}
