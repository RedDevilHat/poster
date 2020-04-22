<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private EntityManagerInterface $entityManager;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function createUser(): User
    {
        return new User();
    }

    public function updatePassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();

        if ('' === $plainPassword) {
            return;
        }

        $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        $user->eraseCredentials();
    }

    public function updateUser(User $user, $andFlush = true): void
    {
        $this->updatePassword($user);

        $this->entityManager->persist($user);
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }
}
