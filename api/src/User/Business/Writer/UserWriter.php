<?php

namespace App\User\Business\Writer;

use App\Kernel\Business\ValueObject\ResourceResponseVO;
use App\User\Business\Mapper\UserMapperInterface;
use App\User\Business\ValueObject\UserVO;
use App\User\Persistence\Doctrine\Entity\User;
use App\User\Persistence\Doctrine\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserWriter implements UserWriterInterface
{
    public function __construct(
        private UserMapperInterface $userMapper,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * {@inheritDoc}
     */
    public function create(UserVO $userVO, User $userContext): ResourceResponseVO
    {
        $user = $this->userMapper->mapUserVOToUser($userVO, new User());

        $plaintextPassword = $user->getPassword();

        // Hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $user->setCreatedBy($userContext);

        $this->userRepository->save($user);

        return (new ResourceResponseVO())
            ->setData($user);
    }

    /**
     * {@inheritDoc}
     */
    public function update(UserVO $userVO, User $user): ResourceResponseVO
    {
        $user = $this->userMapper->mapUserVOToUser($userVO, $user);

        $this->userRepository->save($user);

        return (new ResourceResponseVO())
            ->setData($user);
    }
}
