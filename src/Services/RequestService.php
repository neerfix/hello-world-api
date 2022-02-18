<?php

namespace App\Services;

use DateTimeZone;
use Symfony\Component\HttpFoundation\Request;

class RequestService
{
    public function __construct(
        private string $defaultTimezone,
    ) {
        $this->defaultTimezone = DateTimeZone::EUROPE;
    }

    public function getTimezone(Request $request): DateTimeZone
    {
        $timezone = $request->headers->get('HW-TIMEZONE');

        if (empty($timezone) || !$this->isTimezoneValid($timezone)) {
            $timezone = $this->defaultTimezone;
        }

        return new DateTimeZone($timezone);
    }

    private function isTimezoneValid(string $timezone): bool
    {
        return in_array($timezone, DateTimeZone::listIdentifiers(), true);
    }
}
