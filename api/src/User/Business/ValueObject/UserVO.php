<?php

namespace App\User\Business\ValueObject;

use App\Kernel\Business\PropertyAccess\ArrayObject\ArrayObjectInterface;
use App\Kernel\Business\PropertyAccess\ArrayObject\ArrayObjectTrait;
use App\Kernel\Business\Validator\Constraint as AppAssert;
use App\Kernel\Business\ValueObject\ResourceVO;
use App\User\Persistence\Doctrine\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserVO extends ResourceVO implements ArrayObjectInterface
{
    use ArrayObjectTrait;

    #[Assert\NotBlank(groups: ['create', 'register'])]
    #[Assert\Length(max: 254, groups: ['create', 'update', 'register'])]
    #[Assert\Email(mode: 'strict', groups: ['create', 'update', 'register'])]
    #[Assert\NoSuspiciousCharacters(
        // Checks zero-width spaces and numbers looking the same (e.g. 8 and ৪)
        checks: Assert\NoSuspiciousCharacters::CHECK_INVISIBLE | Assert\NoSuspiciousCharacters::CHECK_MIXED_NUMBERS,
        restrictionLevel: Assert\NoSuspiciousCharacters::RESTRICTION_LEVEL_HIGH,
        groups: ['create', 'update', 'register']
    )]
    #[AppAssert\EntityRelationNotFound(
        entity: User::class,
        relation: '$email',
        groups: ['create', 'update', 'register']
    )]
    protected string $email;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(max: 50, groups: ['create', 'update'])]
    #[Assert\PasswordStrength(
        minScore: Assert\PasswordStrength::STRENGTH_STRONG,
        groups: ['create', 'update', 'register']
    )]
    protected string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserVO
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): UserVO
    {
        $this->password = $password;
        return $this;
    }
}
