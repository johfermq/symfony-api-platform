<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends BaseRepository
{
    /**
     * @return string
     */
    protected static function entityClass(): string
    {
        return User::class;
    }

    /**
     * @param string $id
     * @return User|object|null
     */
    public function findOneById(string $id)
    {
        return $this->objectRepository->find($id);
    }

    /**
     * @param string $email
     * @return User|object|null
     */
    public function findOneByEmail(string $email)
    {
        return $this->objectRepository->findOneBy(['email' => $email]);
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->saveEntity($user);
    }
}