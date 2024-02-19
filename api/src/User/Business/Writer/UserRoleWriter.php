<?php
//
//namespace App\User\Business\Writer;
//
//use App\Kernel\Business\ValueObject\CollectionResponseVO;
//use App\Kernel\Communication\HttpKernel\Exception\BadRequestHttpException;
//use App\User\Business\Builder\UserRoleBuilder;
//use App\User\Business\ValueObject\UserRoleVO;
//use App\User\Persistence\Doctrine\Entity\User;
//use App\User\Persistence\Doctrine\Repository\UserRoleRepository;
//
//class UserRoleWriter implements UserRoleWriterInterface
//{
//    public function __construct(
//        private readonly UserRoleBuilder $userRoleBuilder,
//        private readonly UserRoleRepository $userRoleRepository
//    ) {}
//
//    /**
//     * {@inheritDoc}
//     */
//    public function create(UserRoleVO $userRoleVO, User $user): CollectionResponseVO
//    {
//        $userRoles = $this->userRoleBuilder->buildCollection($userRoleVO, $user);
//
//        $this->userRoleRepository->addCollection($userRoles);
//
//        return (new CollectionResponseVO())
//            ->setData($userRoles);
//    }
//
//    /**
//     * Changing roles of a user is allowed under following criteria:
//     *
//     * 1. Only admin user can change privileges of users.
//     * 2. An admin user cannot remove its own admin privilege.
//     * 3. Every user must have at least one user role.
//     *
//     * {@inheritDoc}
//     */
//    public function update(UserRoleVO $userRoleVO, User $user): CollectionResponseVO
//    {
//        $userRoles = $this->userRoleBuilder->buildCollection($userRoleVO, $user);
//
//        $loggedInUser = $userRoleVO->getUserContext();
//
//        // Case 1.
//        if (!$loggedInUser->isAdmin() && !$loggedInUser->isSuperAdmin()) {
//            throw new BadRequestHttpException('Only admin can change privileges of users.');
//        }
//
//        // Case 2.
//        if (($loggedInUser->getId() === $user->getId()) && $user->isAdmin() && !$userRoles->containsAdminRole()) {
//            $userRoles[] = $this->userRoleBuilder->buildAdmin($user);
//        }
//
//        // Case 3.
//        if (!$userRoles->count()) {
//            throw new BadRequestHttpException('At least one role required.');
//        }
//
//        $this->userRoleRepository->removeCollection(
//            $user->getUserRoles()->diff($userRoles), true
//        );
//
//        $this->userRoleRepository->addCollection(
//            $userRoles->diff($user->getUserRoles())
//        );
//
//        return (new CollectionResponseVO())
//            ->setData($userRoles);
//    }
//}
