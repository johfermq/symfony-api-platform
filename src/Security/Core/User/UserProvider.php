<?php

namespace App\Security\Core\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UserProvider constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username
     * @return User
     */
    private function findUser(string $username): User
    {
        $user = $this->userRepository->findOneByEmail($username);

        if ($user === null)
        {
            throw new UsernameNotFoundException("User with email {$username} not found");
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     * @param string $newEncodedPassword
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        $user->setPassword($newEncodedPassword);

        $this->userRepository->save($user);
    }

    /**
     * @param string $username
     * @return User|UserInterface
     */
    public function loadUserByUsername(string $username)
    {
        return $this->findUser($username);
    }

    /**
     * @param $username
     * @param array $payload
     * @return UserInterface
     */
    public function loadUserByUsernameAndPayload($username, array $payload): UserInterface
    {
        return $this->findUser($username);
    }

    /**
     * @param UserInterface $user
     * @return User|UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (! ($user instanceof User))
        {
            throw new UnsupportedUserException(
                \sprintf('Instances of %s are not supported', \get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass(string $class)
    {
        return User::class === $class;
    }
}