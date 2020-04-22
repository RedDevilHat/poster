<?php

declare(strict_types=1);

namespace App\Tests\Functional;

class Oauth2Test extends WebTestCase
{
    public function testDashboard(): void
    {
        $client = $this->getDefaultUser()->getApplications()->first();

        $this->logIn();

        $crawler = $this->client->request(
            'GET',
            $this->generateUrl(
                'fos_oauth_server_authorize',
                [
                    'client_id' => $client->getPublicId(),
                    'redirect_uri' => $client->getRedirectUris()[0],
                    'response_type' => 'token',
                ]
            )
        );

        $form = $crawler->filter('form')->form();
        $this->client->request(
            'POST',
            $this->generateUrl('fos_oauth_server_authorize'),
            \array_merge(
                $form->getPhpValues(),
                [
                    'accepted' => '',
                ]
            )
        );

        $location = $this->client->getResponse()->headers->get('location');

        \parse_str(\parse_url($location, PHP_URL_FRAGMENT), $fragments);

        self::assertResponseIsSuccessful();

        $this->assertArrayHasKey('access_token', $fragments);
    }
}
