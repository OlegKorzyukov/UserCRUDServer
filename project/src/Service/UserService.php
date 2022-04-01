<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserValidateException;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;

class UserService
{
    private EntityManagerInterface $entityManager;
    private UserValidator $validator;
    private UserRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserValidator         $validator,
        UserRepository         $repository
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->repository = $repository;
    }

    /**
     * @throws UserValidateException
     */
    public function createUser(string $name, string $email): void
    {
        $user = new User($name, $email);
        $this->validator->validateUser($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateUser(int $userId, string $name, string $email): void
    {
        $user = $this->repository->find($userId);
        if (!$user) {
            throw new EntityNotFoundException();
        }
        $user->setName($name);
        $user->setEmail($email);
        $this->entityManager->flush($user);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->repository->find($userId);
        if (!$user) {
            throw new EntityNotFoundException();
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
