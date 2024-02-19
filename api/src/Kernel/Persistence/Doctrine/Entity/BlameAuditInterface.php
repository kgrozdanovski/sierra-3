<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

use App\User\Persistence\Doctrine\Entity\User;

interface BlameAuditInterface
{
    /**
     * @return User
     */
    public function getCreatedBy(): User;

    /**
     * @param User $createdBy
     *
     * @return $this
     */
    public function setCreatedBy(User $createdBy): self;

    /**
     * @return User
     */
    public function getModifiedBy(): User;

    /**
     * @param User $modifiedBy
     *
     * @return $this
     */
    public function setModifiedBy(User $modifiedBy): self;
}
