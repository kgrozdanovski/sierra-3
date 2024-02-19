<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

use App\User\Persistence\Doctrine\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

trait BlameAuditTrait
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'created_by', nullable: false)]
    #[Groups(['metadata'])]
    #[SerializedName('created_by')]
    private User $createdBy;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'modified_by', nullable: false)]
    #[Groups(['metadata'])]
    #[SerializedName('modified_by')]
    private User $modifiedBy;

    /**
     * @return User
     */
    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     *
     * @return BlameAuditInterface
     */
    public function setCreatedBy(User $createdBy): BlameAuditInterface
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return User
     */
    public function getModifiedBy(): User
    {
        return $this->modifiedBy;
    }

    /**
     * @param User $modifiedBy
     *
     * @return BlameAuditInterface
     */
    public function setModifiedBy(User $modifiedBy): BlameAuditInterface
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }
}
