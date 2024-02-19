<?php

namespace App\User\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Kernel\Persistence\Doctrine\Entity\EntityAuditInterface;
use App\Kernel\Persistence\Doctrine\Entity\EntityAuditTrait;
use App\Kernel\Persistence\Doctrine\Audit\Attribute as Audit;
use App\User\Persistence\Doctrine\Collection\UserRoleCollection;
use App\User\Persistence\Doctrine\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[Audit\Entity(name: UserAudit::class)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements EntityAuditInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    use EntityAuditTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups(['user_resource', 'user_collection'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user_resource', 'user_collection'])]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;   // The hashed password

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserRole::class, cascade: ['persist', 'remove'])]
    private Collection $userRoles;

    private Collection $roles;

    public function __construct()
    {
        $this->userRoles = new UserRoleCollection();
    }

    public function getId(): ?string
    {
        return (string) $this->id->toRfc4122();
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
        // Guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        foreach ($this->userRoles as $userRole) {
            $roles[] = $userRole->getRole();
        }

        return $roles;
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
