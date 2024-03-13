<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

use App\Kernel\Persistence\Doctrine\Generator\UuidV4Generator;
use App\User\Persistence\Doctrine\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * Class AbstractAudit
 *
 * Warning: Generated columns must be defined in the inheriting entity, they will not be
 * registered within this MappedSuperclass.
 *
 * @class AbstractAudit
 * @package App\Kernel\Persistence\Doctrine\Entity
 */
#[ORM\MappedSuperclass]
abstract class AbstractAudit
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?UuidV4 $id = null;

    #[ORM\Column(name: 'request_id')]
    protected Uuid $requestId;

    #[ORM\Column(name: 'entity_id')]
    protected EntityInterface $entity;

    #[ORM\Column(name: 'property_name', length: 128)]
    protected string $propertyName;

    #[ORM\Column(name: 'original_value_string', length: 256)]
    protected string $originalValueString;

    #[ORM\Column(name: 'updated_value_string', length: 256)]
    protected string $updatedValueString;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'created_by')]
    protected User $createdBy;

    #[ORM\Column(name: 'created_at')]
    protected \DateTimeImmutable $createdAt;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return (string) $this->id->toRfc4122();
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return (string) $this->requestId->toRfc4122();
    }

    /**
     * @param Uuid $requestId
     *
     * @return $this
     */
    public function setRequestId(Uuid $requestId): static
    {
        $this->requestId = $requestId;

        return $this;
    }

    /**
     * @return EntityInterface
     */
    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return $this
     */
    public function setEntity(EntityInterface $entity): static
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName
     *
     * @return $this
     */
    public function setPropertyName(string $propertyName): static
    {
        $this->propertyName = $propertyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalValueString(): string
    {
        return $this->originalValueString;
    }

    /**
     * @param string $originalValueString
     *
     * @return $this
     */
    public function setOriginalValueString(string $originalValueString): static
    {
        $this->originalValueString = $originalValueString;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedValueString(): string
    {
        return $this->updatedValueString;
    }

    /**
     * @param string $updatedValueString
     *
     * @return $this
     */
    public function setUpdatedValueString(string $updatedValueString): static
    {
        $this->updatedValueString = $updatedValueString;

        return $this;
    }

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
     * @return $this
     */
    public function setCreatedBy(User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

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
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
