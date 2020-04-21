<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\OAuth2\Client;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
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
     * @var GroupInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\UserGroup")
     * @ORM\JoinTable(name="users_user_groups",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var Collection|Client[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OAuth2\Client", mappedBy="owner")
     */
    private $applications;

    public function __construct()
    {
        parent::__construct();
        $this->applications = new ArrayCollection();
    }

    /**
     * @return Client[]|Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }
}
