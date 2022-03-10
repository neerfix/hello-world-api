<?php

namespace App\Services;

use App\Entity\Login;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class LoginService
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(User $user, string $applicationVersion, string $ipAddress, string $userAgent, ?string $failureReason = null): Login
    {
        $login = (new Login())
            ->setUserId($user)
            ->setApplicationVersion($applicationVersion)
            ->setIpAddress($ipAddress)
            ->setUserAgent($userAgent);

        if (null !== $failureReason) {
            $login
                ->setFailureReason($failureReason)
                ->setIsSuccessful(false)
            ;
        }

        $this->em->persist($login);
        $this->em->flush();

        return $login;
    }
}
