<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class WebTestCase extends BaseWebTestCase
{
    use FixturesTrait;

    protected ?KernelBrowser $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([
            AppFixtures::class,
        ]);
    }

    protected function generateUrl($name, $parameters = [], $referenceType = RouterInterface::ABSOLUTE_PATH): string
    {
        /** @var RouterInterface $router */
        $router = self::$container->get('router');

        return $router->generate($name, $parameters, $referenceType);
    }

    protected function logIn(): void
    {
        $session = self::$container->get('session');

        $user = $this->getDefaultUser();
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $session->set('_security_main', \serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function getDefaultUser(): User
    {
        return self::$container->get('doctrine.orm.default_entity_manager')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'root@example.com'])
        ;
    }
}
