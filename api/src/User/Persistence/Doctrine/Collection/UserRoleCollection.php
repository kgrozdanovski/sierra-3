<?php

namespace App\User\Persistence\Doctrine\Collection;

use App\User\Persistence\Doctrine\Entity\Role;
use App\User\Persistence\Doctrine\Entity\UserRole;
use Doctrine\Common\Collections\ArrayCollection;

class UserRoleCollection extends ArrayCollection
{
    /**
     * @param int $roleId
     *
     * @return bool
     */
    public function containsRoleById(int $roleId): bool
    {
        return $this->exists(function ($key, UserRole $userRole) use ($roleId) {
            return $userRole->getRole()->getId() === $roleId;
        });
    }

    /**
     * @param UserRoleCollection $collection
     *
     * @return UserRoleCollection
     */
    public function diff(UserRoleCollection $collection): UserRoleCollection
    {
        return $this->filter(function (UserRole $userRole) use ($collection) {
            return !$collection->containsRoleById($userRole->getRole()->getId());
        });
    }
}
