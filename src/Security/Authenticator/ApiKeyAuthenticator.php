<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
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
    private const API_USER_HEADER = 'X-USER-KEY';

    public function __construct(private PasswordHasherFactoryInterface $hasherFactory) {}

    public function supports(Request $request): ?bool
    {
        return
            $request->headers->has(self::API_KEY_HEADER) &&
            $request->headers->has(self::API_USER_HEADER)
        ;
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get(self::API_KEY_HEADER);
        $username = $request->headers->get(self::API_USER_HEADER);

        if (null === $username) {
            throw new CustomUserMessageAuthenticationException('No API username provided');
        }

        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        return new Passport(
            new UserBadge($username),
            new CustomCredentials(function ($apiToken, User $user) {
                $salt = $user->getSalt();

                if (!$user->getApiKey()) {
                    return false;
                }

                $hasher = $this->hasherFactory->getPasswordHasher($user);
                return $hasher->verify($user->getApiKey(), $apiToken, $salt);
            }, $apiToken)
        );
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
        return new Response('Auth headers required', Response::HTTP_UNAUTHORIZED);
    }
}
