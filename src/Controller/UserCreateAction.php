<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserCreateAction
{
    public function __construct(private PasswordHasherFactoryInterface $hasherFactory) {}

    public function __invoke(User $data): User
    {
        if (!$data->getPassword() || !$data->getUsername() || empty($data->getRoles())) {
            throw new BadRequestHttpException('Data missing');
        }

        $hasher = $this->hasherFactory->getPasswordHasher($data);
        $data->setPassword($hasher->hash($data->getPassword()));

        return $data;
    }
}
