<?php

namespace App\User\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use App\Kernel\Persistence\Doctrine\Audit\Attribute as Audit;
use App\Kernel\Persistence\Doctrine\Entity\EntityInterface;
use App\Kernel\Persistence\Doctrine\Entity\EntityTrait;
use App\User\Persistence\Doctrine\Repository\UserRoleRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[Audit\Entity(name: UserRoleAudit::class)]
#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
class UserRole implements EntityInterface
{
    use EntityTrait;

    #[ORM\Id]
    #[ORM\Column(type: "guid")]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'roles')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'role_id', nullable: false)]
    private ?Role $role = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;

        return $this;
    }
}
