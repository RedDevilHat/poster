<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * @internal
 */
final class AuthenticationTokenTest extends WebTestCase
{
    use FixturesTrait;

    public function testAuthenticationToken(): void
    {
        $client = static::createClient();

        $this->loadFixtures([
            AppFixtures::class,
        ]);

        $jsonEncoder = new JsonEncoder();

        /** @var string $payload */
        $payload = $jsonEncoder->encode([
            'username' => 'root@example.com',
            'password' => 'root@example.com',
        ], JsonEncoder::FORMAT);

        $client->request('POST', '/api/authentication_token', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], $payload);

        /** @var array<string, string> $data */
        $data = $jsonEncoder->decode((string) $client->getResponse()->getContent(), JsonEncoder::FORMAT);

        static::assertArrayHasKey('token', $data);

        $client->request('GET', '/api/posts', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Accept' => 'application/json',
            'HTTP_Authorization' => \sprintf('Bearer %s', $data['token']),
        ]);

        /** @var array<array<string, string>> $data */
        $data = $jsonEncoder->decode((string) $client->getResponse()->getContent(), JsonEncoder::FORMAT);

        static::assertTrue(\count($data) > 0);
    }
}
