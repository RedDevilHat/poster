<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web;

use App\DataFixtures\AppFixtures;
use App\Tests\Functional\Traits\SecurityTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
final class MainControllerTest extends WebTestCase
{
    use FixturesTrait;
    use SecurityTrait;

    public function testMainRedirect(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        self::assertResponseRedirects();
    }

    public function testMain(): void
    {
        $client = static::createClient();
        $this->loadFixtures([
            AppFixtures::class,
        ]);

        $this->logIn($client, 'root@example.com');

        $client->request('GET', '/api/docs');

        self::assertResponseIsSuccessful();
    }
}
