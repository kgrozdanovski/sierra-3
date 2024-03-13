<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait SoftDeleteAuditTrait
{
    #[ORM\Column(name: 'deleted')]
    #[Groups(['metadata'])]
    private bool $deleted = false;

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     *
     * @return SoftDeleteAuditInterface
     */
    public function setDeleted(bool $deleted): SoftDeleteAuditInterface
    {
        $this->deleted = $deleted;

        return $this;
    }
}
