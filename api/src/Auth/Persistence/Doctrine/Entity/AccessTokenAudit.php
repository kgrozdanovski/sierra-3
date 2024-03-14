<?php

namespace App\Auth\Persistence\Doctrine\Entity;

use App\Kernel\Persistence\Doctrine\Entity\AbstractAudit;
use App\Kernel\Persistence\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'access_token_audit')]
#[ORM\Entity]
class AccessTokenAudit extends AbstractAudit
{
    #[ORM\ManyToOne(targetEntity: AccessToken::class)]
    #[ORM\JoinColumn(name: 'entity_id')]
    protected EntityInterface $entity;
}
