<?php

namespace App\User\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use App\Kernel\Persistence\Doctrine\Audit\Attribute as Audit;
use App\Kernel\Persistence\Doctrine\Entity\EntityInterface;
use App\Kernel\Persistence\Doctrine\Entity\EntityTrait;
use App\User\Persistence\Doctrine\Repository\RoleRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[Audit\Entity(name: RoleAudit::class)]
#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role implements EntityInterface
{
    use EntityTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): ?string
    {
        return (string) $this->id->toRfc4122();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
