<?php

namespace App\Security;

use App\Repository\TokenRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\TokenService;
use App\Services\UserService;
use App\Utils\AuthToken;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class AppAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private RequestService $requestService,
        private ResponseService $responseService,
        private TokenService $tokenService,
        private UserService $userService,
        private TokenRepository $tokenRepository,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        if (!($request->headers->has(RequestService::AUTHORIZATION_HEADER))) {
            return false;
        }

        return $this->requestService->isAuthorizationHeaderTypeValid($request, RequestService::AUTHORIZATION_HEADER_TYPE_BEARER);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function authenticate(Request $request): Passport
    {
        $apiToken = $this->requestService->getAuthorizationToken($request);
        $token = $this->tokenRepository->findOneByValue($apiToken);

        if (null === $apiToken || null === $token) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $user = $this->userService->getUserByToken($token);

        return new SelfValidatingPassport(new UserBadge($user->getEmail()));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (empty($credentials['access_token'])) {
            return null;
        }

        $token = $this->tokenService->findOneByTokenAndType(AuthToken::TYPE_ACCESS_TOKEN, $credentials['access_token']);

        if (null === $token) {
            return null;
        }

        return $userProvider->loadUserByUsername($token->getUserUuid());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * @throws Exception
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->responseService->error401();
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
