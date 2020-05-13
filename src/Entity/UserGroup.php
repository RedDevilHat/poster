<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Nonstandard\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user_groups")
 * @ORM\Entity(repositoryClass="App\Repository\UserGroupRepository")
 * @UniqueEntity(fields={"name"})
 */
class UserGroup
{
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
    private string $name;

    /**
     * @var array<string>
     *
     * @ORM\Column(type="json")
     */
    private array $roles;

    /**
     * @param array<string> $roles
     */
    public function __construct(string $name, array $roles = [])
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return \array_values(\array_unique($this->roles));
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
}
