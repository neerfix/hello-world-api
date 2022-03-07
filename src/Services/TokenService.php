<?php

namespace App\Services;

use App\Entity\Token;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TokenService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    // ------------------------------ >

    /**
     * @throws Exception
     */
    public function RandomToken(): string
    {
        $length = 32;

        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length));
        }
        if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }

        return '';
    }

    public function deleteToken(Token $token): void
    {
        $this->em->remove($token);
        $this->em->flush();
    }

    /**
     * @throws Exception
     */
    public function create(User $user, string $target, ?DateTime $datetime = null): Token
    {
        $tokenStr = $this->RandomToken();
        $expirationDate = ($datetime) ?? new DateTime('+1 hour');

        $token = (new Token())
            ->setUser($user)
            ->setValue($tokenStr)
            ->setTarget($target)
            ->setExpirationDate($expirationDate);

        $this->em->persist($token);
        $this->em->flush();

        return $token;
    }
}
