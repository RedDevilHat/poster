<?php

declare(strict_types=1);

namespace App\Tests\Functional;

class AdminControllerTest extends WebTestCase
{
    public function testDashboard(): void
    {
        $this->logIn();
        $this->client->request('GET', '/admin/dashboard');

        self::assertResponseIsSuccessful();
    }
}
