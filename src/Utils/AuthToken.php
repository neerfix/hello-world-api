<?php

namespace App\Utils;

use Symfony\Component\Serializer\Annotation\Groups;

class AuthToken
{
    // ------------------------------ >

    public const TYPE_ACCESS_TOKEN = 'access_token';
    public const TYPE_REFRESH_TOKEN = 'refresh_token';

    public const EXPIRE = 10800;

    // ------------------------------ >

    private string $userUuid;

    /**
     * @Groups({"auth_token"})
     */
    private ?string $accessToken;

    /**
     * @Groups({"auth_token"})
     */
    private ?string $refreshToken;

    /**
     * @Groups({"auth_token"})
     */
    private string $authType;

    /**
     * @Groups({"auth_token"})
     */
    private ?int $expiresIn;

    // ------------------------------ >

    public function __construct(
        string $authType,
        string $userUuid,
        ?string $accessToken = null,
        ?string $refreshToken = null,
        ?int $expiresIn = null
    ) {
        $this->authType = $authType;
        $this->userUuid = $userUuid;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresIn = $expiresIn;
    }

    // ------------------------------ >

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): AuthToken
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): AuthToken
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getAuthType(): ?string
    {
        return $this->authType;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }
}
