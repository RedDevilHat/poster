<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $defaultGroup = new UserGroup('Default');
        $defaultGroup->addRole('ROLE_ADMIN');
        $manager->persist($defaultGroup);

        $user = new User('root@example.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'root@example.com'));
        $user->addRole('ROLE_SUPER_ADMIN');
        $user->addGroup($defaultGroup);
        $manager->persist($user);

        $user = new User('user@example.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'user@example.com'));
        $manager->persist($user);

        for ($i = 1; $i < 11; ++$i) {
            $post = new Post(\sprintf('Post №%s', $i));
            $post->setContent('Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.');
            $manager->persist($post);
        }

        $manager->flush();
    }
}
