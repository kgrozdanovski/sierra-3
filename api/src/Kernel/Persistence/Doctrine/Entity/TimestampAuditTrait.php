<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

trait TimestampAuditTrait
{
    #[ORM\Column(name: 'created_at', nullable: false, insertable: false, updatable: false)]
    #[Groups(['metadata'])]
    #[SerializedName('created_at')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'modified_at', nullable: false, insertable: false)]
    #[Groups(['metadata'])]
    #[SerializedName('modified_at')]
    private \DateTimeImmutable $modifiedAt;

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     *
     * @return TimestampAuditInterface
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): TimestampAuditInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getModifiedAt(): \DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    /**
     * @param \DateTimeImmutable $modifiedAt
     *
     * @return TimestampAuditInterface
     */
    public function setModifiedAt(\DateTimeImmutable $modifiedAt): TimestampAuditInterface
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }
}
