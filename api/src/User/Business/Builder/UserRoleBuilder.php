<?php

namespace App\User\Business\Builder;

use App\User\Business\ValueObject\UserRoleVO;
use App\User\Persistence\Doctrine\Collection\UserRoleCollection;
use App\User\Persistence\Doctrine\Entity\User;
use App\User\Persistence\Doctrine\Entity\UserRole;
use App\User\Persistence\Doctrine\Repository\RoleRepository;

class UserRoleBuilder
{
    public function __construct(
        private readonly RoleRepository $roleRepository
    ) {
    }

    /**
     * @param array $roles
     * @param User $user
     *
     * @return UserRoleCollection
     */
    public function buildCollection(array $roles, User $user): UserRoleCollection
    {
        $userRoles = new UserRoleCollection();
        $roles = $this->roleRepository->findBy(['id' => $roles]);

        foreach ($roles as $role) {
            $userRole = new UserRole();
            $userRole->setRole($role);
            $userRole->setUser($user);

            // Metadata is set by EntityTraits, see Entity/UserRole.php

            $userRoles[] = $userRole;
        }

        return $userRoles;
    }
}
