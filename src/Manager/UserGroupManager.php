<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\UserGroup;
use Doctrine\ORM\EntityManagerInterface;

class UserGroupManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createGroup($name): UserGroup
    {
        return new UserGroup($name);
    }

    public function updateGroup(UserGroup $group, $andFlush = true): void
    {
        $this->entityManager->persist($group);
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }
}
