<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\GroupManager;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\GroupManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var GroupManager
     */
    private $groupManager;

    /**
     * AppFixtures constructor.
     *
     * @param UserManagerInterface  $userManager
     * @param GroupManagerInterface $groupManager
     */
    public function __construct(UserManagerInterface $userManager, GroupManagerInterface $groupManager)
    {
        $this->userManager = $userManager;
        $this->groupManager = $groupManager;
    }

    public function load(ObjectManager $manager): void
    {
        $defaultGroup = $this->groupManager->createGroup('Default');
        $defaultGroup->addRole('ROLE_ADMIN');
        $this->groupManager->updateGroup($defaultGroup, false);

        /** @var User $user */
        $user = $this->userManager->createUser();
        $user
            ->setUsername('root@example.com')
            ->setEmail('root@example.com')
            ->setPlainPassword('root@example.com')
            ->setEnabled(true)
            ->addRole(UserInterface::ROLE_SUPER_ADMIN)
            ->addGroup($defaultGroup)
        ;
        $this->userManager->updateUser($user);

        /** @var User $user */
        $user = $this->userManager->createUser();
        $user
            ->setUsername('user@example.com')
            ->setEmail('user@example.com')
            ->setPlainPassword('user@example.com')
            ->setEnabled(true)
        ;
        $this->userManager->updateUser($user);

        for ($i = 1; $i < 11; ++$i) {
            $post = new Post(\sprintf('Post №%s', $i));
            $post->setContent('Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.');
            $manager->persist($post);
        }

        $manager->flush();
    }
}
