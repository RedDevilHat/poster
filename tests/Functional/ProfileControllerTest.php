<?php

declare(strict_types=1);

namespace App\Tests\Functional;

class ProfileControllerTest extends WebTestCase
{
    public function testDashboard(): void
    {
        $this->logIn();
        $this->client->request('GET', '/profile/application');

        self::assertResponseIsSuccessful();
    }
}
