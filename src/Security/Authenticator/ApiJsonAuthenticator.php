<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class ApiJsonAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(private ManagerRegistry $registry, private PasswordHasherFactoryInterface $hasherFactory) {}

    public function supports(Request $request): ?bool
    {
        if ('application/json' !== $request->headers->get('Content-Type')) {
            return false;
        }

        $body = json_decode($request->getContent(), true);

        return isset($body['username'], $body['password']);
    }

    public function authenticate(Request $request): Passport
    {
        $body = json_decode($request->getContent(), true);

        return new Passport(
            new UserBadge($body['username']),
            new CustomCredentials(function ($password, User $user) {
                $hasher = $this->hasherFactory->getPasswordHasher($user);

                if (!$user->getPassword()) {
                    return false;
                }

                return $hasher->verify($user->getPassword(), $password, $user->getSalt());
            }, $body['password'])
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new Response('Auth header required', Response::HTTP_UNAUTHORIZED);
    }
}
