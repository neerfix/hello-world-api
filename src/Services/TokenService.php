<?php

namespace App\Services;

use App\Entity\Token;
use App\Entity\User;
use App\Repository\TokenRepository;
use App\Utils\AuthToken;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use RuntimeException;

class TokenService
{
    public function __construct(
        private EntityManagerInterface $em,
        private TokenRepository $tokenRepository,
    ) {
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

    public function deleteToken(AuthToken $token): void
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
        $expirationDate = ($datetime) ?? new DateTime('+1 day');

        $token = (new Token())
            ->setUser($user)
            ->setValue($tokenStr)
            ->setTarget($target)
            ->setExpirationDate($expirationDate);

        $this->em->persist($token);
        $this->em->flush();

        return $token;
    }

    // ------------- Auth Token ----------------- >

    /**
     * @throws Exception
     */
    public function createAuth(User $user): AuthToken
    {
        $refreshToken = $this->tokenRepository->findRefreshTokenByUser($user);

        $accessToken = $this->create($user, Token::TYPE_ACCESS_TOKEN);
        $refreshToken ??= $this->create($user, Token::TYPE_REFRESH_TOKEN);

        $this->create($user, Token::TYPE_ACCESS_TOKEN);

        return new AuthToken(
            'email',
            $user->getUuid(),
            $accessToken->getValue(),
            $refreshToken->getValue(),
            AuthToken::EXPIRE
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function findOneByTokenAndType(string $type, string $tokenValue): ?AuthToken
    {
        $token = $this->tokenRepository->findOneByValue($tokenValue);

        if (null !== $token) {
            throw new RuntimeException('le token est invalide');
        }

        $accessToken = (AuthToken::TYPE_ACCESS_TOKEN === $type) ? $token : null;
        $refreshToken = (AuthToken::TYPE_REFRESH_TOKEN === $type) ? $token : null;

        return new AuthToken(
            'email',
            $token->getUser()->getUuid(),
            $accessToken,
            $refreshToken,
            AuthToken::EXPIRE
        );
    }
}
