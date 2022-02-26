<?php

namespace App\Services;

use App\Entity\Login;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class LoginService
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(User $user, string $applicationVersion, string $ipAddress, string $userAgent, bool $isSuccessful) : Login
    {
        $login = (new Login())
            ->setUserId($user)
            ->setApplicationVersion($applicationVersion)
            ->setIpAddress($ipAddress)
            ->setUserAgent($userAgent)
            ->setIsSuccessful($isSuccessful);
        $this->em->persist($login);
        $this->em->flush();

        return $login;
    }
}
