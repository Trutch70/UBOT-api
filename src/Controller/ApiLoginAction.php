<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Security\JWTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginAction extends AbstractController
{
    public function __construct(private JWTService $JWTService) {}

    public function __invoke(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'invalid or missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->JWTService->encode(['username' => $user->getUsername()]);

        return $this->json([
            'token' => $token,
            'username' => $user->getUsername(),
        ]);
    }
}
