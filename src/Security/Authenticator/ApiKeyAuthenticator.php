<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class ApiKeyAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    private const API_KEY_HEADER = 'X-API-KEY';
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::API_KEY_HEADER);
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get(self::API_KEY_HEADER);
        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        return new Passport(new UserBadge($apiToken, function ($apiToken) {
            return $this->registry->getRepository(User::class)->findOneBy([
                'apiKey' => $apiToken
            ]);
        }), new CustomCredentials(function () {return true;}, $apiToken));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'message' => 'Authentication Required',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new Response('Auth header required', Response::HTTP_UNAUTHORIZED);
    }
}
