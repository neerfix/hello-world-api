<?php

namespace App\Services;

use DateTimeZone;
use Symfony\Component\HttpFoundation\Request;

class RequestService
{
    private const DEFAULT_TIMEZONE = 'Europe/Paris';
    public const AUTHORIZATION_HEADER = 'Authorization';

    public const AUTHORIZATION_HEADER_TYPE_BEARER = 'Bearer';
    public const AUTHORIZATION_HEADER_TYPE_BASIC = 'Basic';

    // ---------------------------- >

    public function __construct(
    ) {
    }

    // ---------------------------- >

    public function getTimezone(Request $request): DateTimeZone
    {
        $timezone = $request->headers->get('HW-TIMEZONE');

        if (empty($timezone) || !$this->isTimezoneValid($timezone)) {
            $timezone = static::DEFAULT_TIMEZONE;
        }

        return new DateTimeZone($timezone);
    }

    private function isTimezoneValid(string $timezone): bool
    {
        return in_array($timezone, DateTimeZone::listIdentifiers(), true);
    }

    /**
     * Return if authorization header type is valid according to the authorization type needed.
     */
    public function isAuthorizationHeaderTypeValid(Request $request, string $type): bool
    {
        if (!$request->headers->has(static::AUTHORIZATION_HEADER)) {
            return false;
        }

        if ($this->getAuthorizationHeaderType($request) !== $type) {
            return false;
        }

        return true;
    }

    /**
     * Return the authorization header type.
     */
    public function getAuthorizationHeaderType(Request $request): ?string
    {
        if (!$request->headers->has(static::AUTHORIZATION_HEADER)) {
            return null;
        }

        $authorizationHeader = $request->headers->get(static::AUTHORIZATION_HEADER);

        $isAuthTypeValid = preg_match('/^(Bearer|Basic)\s(\S*)$/', $authorizationHeader, $authorization);

        if (!$isAuthTypeValid) {
            return null;
        }

        return $authorization[1];
    }

    public function getAuthorizationToken(Request $request)
    {
        if (!($request->headers->has(static::AUTHORIZATION_HEADER))) {
            return null;
        }

        $authorizationHeader = $request->headers->get(static::AUTHORIZATION_HEADER);

        $isAuthTypeValid = preg_match('/^(Bearer|Basic)\s(\S*)$/', $authorizationHeader, $authorization);

        if (!$isAuthTypeValid) {
            return null;
        }

        return $authorization[2];
    }
}
