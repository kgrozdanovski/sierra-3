<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

trait EntityAuditTrait
{
    use EntityTrait;
    use BlameAuditTrait;
    use TimestampAuditTrait;
}
