<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Nonstandard\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"})
 */
class User implements UserInterface
{
    public const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $username;

    /**
     * @var array<string>
     *
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var Collection|UserGroup[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\UserGroup")
     * @ORM\JoinTable(name="users_user_groups",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    private $groups;

    /**
     * The hashed password.
     *
     * @ORM\Column(type="string")
     */
    private string $password;

    private ?string $plainPassword = null;

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->password = '';
        $this->id = Uuid::uuid4();
        $this->groups = new ArrayCollection();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        $roles = [$this->roles];

        foreach ($this->getGroups() as $group) {
            $roles[] = $group->getRoles();
        }

        $roles[] = [static::ROLE_DEFAULT];

        return \array_values(\array_unique(\array_merge(...$roles)));
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    public function hasRole(string $role): bool
    {
        return \in_array(\strtoupper($role), $this->getRoles(), true);
    }

    public function addRole(string $role): void
    {
        $role = \strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return;
        }

        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        if (false !== $key = \array_search(\strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = \array_values($this->roles);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @internal
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function addGroup(UserGroup $group): void
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }
    }

    public function removeGroup(UserGroup $group): void
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }
}
