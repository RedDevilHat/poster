<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
final class PostTest extends WebTestCase
{
    use FixturesTrait;

    public function testGetPosts(): void
    {
        $client = static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
            'PHP_AUTH_USER' => 'root@example.com',
            'PHP_AUTH_PW' => 'root@example.com',
        ]);

        $this->loadFixtures([
            AppFixtures::class,
        ]);

        $client->request('GET', '/api/posts');

        self::assertResponseIsSuccessful();
    }
}
