<?php

namespace App\User\Persistence\Doctrine\Entity;

use App\Kernel\Persistence\Doctrine\Entity\BlameAuditInterface;
use App\Kernel\Persistence\Doctrine\Entity\BlameAuditTrait;
use App\Kernel\Persistence\Doctrine\Entity\TimestampAuditInterface;
use App\Kernel\Persistence\Doctrine\Entity\TimestampAuditTrait;
use App\Kernel\Persistence\Doctrine\Generator\ShortIdGenerator;
use App\Kernel\Persistence\Doctrine\Generator\UuidV4Generator;
use App\Kernel\Persistence\Doctrine\Mapping\AppCustomGenerator;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Kernel\Persistence\Doctrine\Entity\EntityAuditInterface;
use App\Kernel\Persistence\Doctrine\Entity\EntityAuditTrait;
use App\Kernel\Persistence\Doctrine\Audit\Attribute as Audit;
use App\User\Persistence\Doctrine\Collection\UserRoleCollection;
use App\User\Persistence\Doctrine\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV4;

#[Audit\Entity(name: UserAudit::class)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements EntityAuditInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    use EntityAuditTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidV4Generator::class)]
    #[Groups(['user_resource', 'user_collection'])]
    private ?UuidV4 $id = null;
    
    #[ORM\Column(type: Types::STRING, length: 7, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[AppCustomGenerator(class: ShortIdGenerator::class)]
    #[Groups(['user_resource', 'user_collection'])]
    private ?string $publicId = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user_resource', 'user_collection'])]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;   // The hashed password

    #[ORM\OneToMany(targetEntity: UserRole::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $userRoles;

    #[Groups(['user_resource', 'user_collection'])]
    private Collection $roles;

    public function __construct()
    {
        $this->userRoles = new UserRoleCollection();
    }

    public function getId(): ?string
    {
        return (string) $this->id->toRfc4122();
    }
    
    public function getPublicId(): ?string
    {
        return $this->publicId;
    }
    
    public function setPublicId(string $publicId): self
    {
        $this->publicId = $publicId;
        
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return UserRoleCollection
     */
    public function getUserRoles(): UserRoleCollection
    {
        return new UserRoleCollection($this->userRoles->toArray());
    }

    /**
     * @param UserRole $userRole
     *
     * @return $this
     */
    public function addUserRole(UserRole $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
            $userRole->setUser($this);
        }

        return $this;
    }

    /**
     * @param UserRole $userRole
     *
     * @return $this
     */
    public function removeEmployeeHub(UserRole $userRole): self
    {
        if ($this->userRoles->removeElement($userRole)) {
            // set the owning side to null (unless already changed)
            if ($userRole->getUser() === $this) {
                $userRole->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        foreach ($this->userRoles as $userRole) {
            $roles[] = $userRole->getRole()->getName();
        }

        return $roles ?? [];
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
