<?php

namespace App\User\Persistence\Doctrine\Collection;

use App\User\Persistence\Doctrine\Entity\Role;
use App\User\Persistence\Doctrine\Entity\UserRole;
use Doctrine\Common\Collections\ArrayCollection;

class UserRoleCollection extends ArrayCollection
{
    public function containsRole(Role $role): bool
    {
        return $this->exists(function ($key, UserRole $userRole) use ($role) {
            return $userRole->getRole()->getId() === $role->getId();
        });
    }
}