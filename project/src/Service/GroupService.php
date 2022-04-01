<?php

namespace App\Service;

use App\Entity\Group;
use App\Exception\GroupValidateException;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityNotFoundException;

class GroupService
{
    private EntityManagerInterface $entityManager;
    private GroupValidator $validator;
    private GroupRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        GroupValidator $validator,
        GroupRepository $repository
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->repository = $repository;
    }

    /**
     * @throws GroupValidateException
     */
    public function createGroup(string $name): void
    {
        $group = new Group($name);
        $this->validator->validateGroup($group);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateGroup(int $groupId, string $name): void
    {
        $group = $this->repository->find($groupId);
        if (!$group) {
            throw new EntityNotFoundException();
        }
        $group->setName($name);
        $this->entityManager->flush($group);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function deleteGroup(int $groupId): void
    {
        $group = $this->repository->find($groupId);
        if (!$group) {
            throw new EntityNotFoundException();
        }
        $this->entityManager->remove($group);
        $this->entityManager->flush();
    }
}
