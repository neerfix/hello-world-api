<?php

namespace App\Services;

use App\Entity\User;

class SecurityService
{
    public function __construct(
    ) {
    }

    public function isSameUser(User $user, string $uuid): bool
    {
        return $user->getUuid() === $uuid;
    }

    public function isAdmin(User $user): bool
    {
        return !in_array(User::ROLE_ADMIN, $user->getRoles(), true);
    }
}
