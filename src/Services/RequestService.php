<?php

namespace App\Services;

use DateTimeZone;
use Symfony\Component\HttpFoundation\Request;

class RequestService
{
    private const DEFAULT_TIMEZONE = 'Europe/Paris';

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
}
