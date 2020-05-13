<?php

declare(strict_types=1);

namespace App\Tests\Functional\Traits;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait SecurityTrait
{
    protected function logIn(KernelBrowser $client, string $username, string $firewall = 'main'): void
    {
        /** @var ContainerInterface $container */
        $container = $client->getContainer();

        /** @var SessionInterface $session */
        $session = $container->get('session');

        /** @var UserRepository $repository */
        $repository = $container->get('doctrine.orm.default_entity_manager')->getRepository(User::class);

        $user = $repository->findOneByUsername($username);

        if (null === $user) {
            throw new \LogicException(\sprintf('User "%s" not found', $username));
        }

        $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        $session->set('_security_'.$firewall, \serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
