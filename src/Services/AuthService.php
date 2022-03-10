<?php

namespace App\Services;

use App\Entity\User;
use App\Utils\AuthToken;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthService
{
    public function __construct(
        private LoginService $loginService,
        private TokenService $tokenService,
        private RequestStack $requestStack,
    ) {
    }

    /**
     * Log a user in from its email.
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function loginFromEmail(User $user): AuthToken
    {
        $request = $this->requestStack->getCurrentRequest();

        $ipAddress = (null !== $request) ? $request->getClientIp() : null;
        $userAgent = (null !== $request) ? $request->headers->get('User-Agent') : null;
        $this->loginService->create($user, '1.1.0', $ipAddress, $userAgent, 'Email or password invalid');

        return $this->tokenService->createAuth($user);
    }
}
