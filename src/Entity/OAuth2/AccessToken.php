<?php

declare(strict_types=1);

namespace App\Entity\OAuth2;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth2_access_tokens")
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Client|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\OAuth2\Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;
}
