<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

interface TimestampAuditInterface
{
    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable;

    /**
     * @param \DateTimeImmutable $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self;

    /**
     * @return \DateTimeImmutable
     */
    public function getModifiedAt(): \DateTimeImmutable;

    /**
     * @param \DateTimeImmutable $modifiedAt
     *
     * @return $this
     */
    public function setModifiedAt(\DateTimeImmutable $modifiedAt): self;
}
