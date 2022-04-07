<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ApiResource(
    collectionOperations: [
        'get' => [
            'security' => false,
        ]
    ],
    itemOperations: [
        'get' => [
            'security' => false,
        ]
    ],
    attributes: ['security' => 'is_granted("ROLE_ADMIN")'],
)]
#[ORM\Entity]
class User implements UserInterface
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255)]
    private string $apiKey;

    #[ORM\Column(type: 'array')]
    private array $roles;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRoles(): array
    {
        $this->roles[] = 'ROLE_USER';
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return $this->apiKey;
    }

    public function setPassword(string $password): void
    {
        $this->apiKey = $password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}
