<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\OAuth2\Client;
use App\Entity\Post;
use App\Manager\UserGroupManager;
use App\Manager\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use FOS\OAuthServerBundle\Entity\ClientManager;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;

final class AppFixtures extends Fixture
{
    private UserManager $userManager;

    private UserGroupManager $groupManager;

    /**
     * @var ClientManager|ClientManagerInterface
     */
    private ClientManagerInterface $clientManager;

    public function __construct(
        UserManager $userManager,
        UserGroupManager $groupManager,
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

        $user = $this->userManager->createUser();
        $user
            ->setUsername('root@example.com')
            ->setEmail('root@example.com')
            ->setPlainPassword('root@example.com')
            ->setEnabled(true)
            ->addRole('ROLE_SUPER_ADMIN')
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
