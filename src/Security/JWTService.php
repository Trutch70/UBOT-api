<?php

declare(strict_types=1);

namespace App\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    public function __construct(private string $apiSalt) {}

    public function encode(array $payload): string
    {
        return JWT::encode($payload, $this->apiSalt, 'HS256');
    }

    public function decode(string $jwt): array
    {
        return (array) JWT::decode($jwt, new Key($this->apiSalt, 'HS256'));
    }
}
