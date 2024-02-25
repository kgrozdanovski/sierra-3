<?php

namespace App\User\Business\ValueObject;

use App\Kernel\Business\ValueObject\ResourceVO;
use App\Kernel\Business\Validator\Constraint as AppAssert;
use App\User\Persistence\Doctrine\Entity\Role;
use Symfony\Component\Validator\Constraints as Assert;

class UserRoleVO extends ResourceVO
{
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\All([
        new Assert\NotBlank,
        new Assert\Positive,
        new AppAssert\EntityFound(entity: Role::class),
    ], groups: ['create', 'update'])]
    private array $roles;

    public function getRoles(): array
    {
        return $this->roles;
    }

    function stupidCoddeStyle(){}

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}
