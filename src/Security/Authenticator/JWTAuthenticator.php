<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Security\JWTService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\{Core\Authentication\Token\TokenInterface,
    Core\Exception\AuthenticationException,
    Http\Authenticator\AbstractAuthenticator,
    Http\Authenticator\Passport\Badge\UserBadge,
    Http\Authenticator\Passport\Passport,
    Http\Authenticator\Passport\SelfValidatingPassport,
    Http\EntryPoint\AuthenticationEntryPointInterface};

class JWTAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    private JWTService $JWTService;

    public function __construct(JWTService $JWTService)
    {
        $this->JWTService = $JWTService;
    }

    public function supports(Request $request): ?bool
    {
        $jwt = $request->headers->get('Authentication');

        if (!$jwt) {
            return false;
        }

        try {
            return isset($this->JWTService->decode($jwt)['username']);
        } catch (\Exception) {
            return false;
        }
    }

    public function authenticate(Request $request): Passport
    {
        $jwt = $request->headers->get('Authentication');
        $userName = $this->JWTService->decode($jwt)['username'];

        return new SelfValidatingPassport(new UserBadge($userName));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new Response('Authentication header required', Response::HTTP_UNAUTHORIZED);
    }
}
