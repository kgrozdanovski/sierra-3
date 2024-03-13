<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

interface SoftDeleteAuditInterface
{
    /**
     * @return bool
     */
    public function isDeleted(): bool;

    /**
     * @param bool $deleted
     *
     * @return $this
     */
    public function setDeleted(bool $deleted): self;
}
