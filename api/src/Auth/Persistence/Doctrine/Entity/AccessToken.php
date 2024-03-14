<?php

namespace App\Auth\Persistence\Doctrine\Entity;

use App\Kernel\Persistence\Doctrine\Entity\BlameAuditInterface;
use App\Kernel\Persistence\Doctrine\Entity\BlameAuditTrait;
use App\Kernel\Persistence\Doctrine\Entity\TimestampAuditInterface;
use App\Kernel\Persistence\Doctrine\Entity\TimestampAuditTrait;
use App\Kernel\Persistence\Doctrine\Generator\UuidV4Generator;
use App\Auth\Persistence\Doctrine\Entity\AccessTokenAudit;
use App\User\Persistence\Doctrine\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Kernel\Persistence\Doctrine\Entity\EntityAuditInterface;
use App\Kernel\Persistence\Doctrine\Entity\EntityAuditTrait;
use App\Kernel\Persistence\Doctrine\Audit\Attribute as Audit;
use App\Auth\Persistence\Doctrine\Repository\AccessTokenRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\UuidV4;

#[Audit\Entity(name: AccessTokenAudit::class)]
#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
#[ORM\Table(name: 'access_token')]
class AccessToken implements EntityAuditInterface
{
    use EntityAuditTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidV4Generator::class)]
    private ?UuidV4 $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private ?User $user = null;
    
    #[ORM\Column(type: 'string', length: 50, unique: true, nullable: false, updatable: false)]
    private string $token;

    #[ORM\Column(name: 'valid_until', nullable: true)]
    #[SerializedName('valid_until')]
    private \DateTimeImmutable $validUntil;

    public function getId(): ?string
    {
        return (string) $this->id->toRfc4122();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getValidUntil(): ?\DateTimeImmutable
    {
        return $this->validUntil;
    }

    public function setValidUntil(\DateTimeImmutable $validUntil): self
    {
        $this->validUntil = $validUntil;

        return $this;
    }
}
