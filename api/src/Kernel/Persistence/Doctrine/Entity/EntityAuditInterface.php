<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

interface EntityAuditInterface extends EntityInterface, BlameAuditInterface, TimestampAuditInterface
{
}
