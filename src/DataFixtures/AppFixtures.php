<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\OAuth2\Client;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use FOS\OAuthServerBundle\Entity\ClientManager;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\UserBundle\Doctrine\GroupManager;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\GroupManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

final class AppFixtures extends Fixture
{
    /**
     * @var UserManager|UserManagerInterface
     */
    private UserManagerInterface $userManager;

    /**
     * @var GroupManager|GroupManagerInterface
     */
    private GroupManagerInterface $groupManager;

    /**
     * @var ClientManager|ClientManagerInterface
     */
    private ClientManagerInterface $clientManager;

    public function __construct(
        UserManagerInterface $userManager,
        GroupManagerInterface $groupManager,
        ClientManagerInterface $clientManager
    ) {
        $this->userManager = $userManager;
        $this->groupManager = $groupManager;
        $this->clientManager = $clientManager;
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
        $this->userManager->updateUser($user, false);
        /** @var Client $client */
        $client = $this->clientManager->createClient();

        $client->setAllowedGrantTypes([
            'authorization_code',
            'token',
            'password',
            'client_credentials',
            'refresh_token',
            'extensions',
        ]);

        $client->setRedirectUris([
            'http://localhost',
            'https://www.getpostman.com/oauth2/callback',
        ]);

        $client->setOwner($user);

        $manager->persist($client);

        /** @var User $user */
        $user = $this->userManager->createUser();
        $user
            ->setUsername('user@example.com')
            ->setEmail('user@example.com')
            ->setPlainPassword('user@example.com')
            ->setEnabled(true)
        ;
        $this->userManager->updateUser($user, false);

        for ($i = 1; $i < 11; ++$i) {
            $post = new Post(\sprintf('Post №%s', $i));
            $post->setContent('Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.');
            $manager->persist($post);
        }

        $manager->flush();
    }
}
