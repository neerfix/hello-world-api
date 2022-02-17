<?php

namespace App\Services;

class TextService
{
    /**
     * Remove the accents of a string.
     */
    public function removeAccents(string $str, string $encoding = 'utf-8'): string
    {
        // convert on htmlentities
        $str = htmlentities($str, ENT_NOQUOTES, $encoding);

        // cut html entities to keep only the first cars(ex: "&ecute;" => "e")
        $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

        // replace ligatures (ex: "Å“" => "oe")
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);

        // remove the rest
        return preg_replace('#&[^;]+;#', '', $str);
    }

    // ------------------------------ >

    public function cleanFirstName(string $firstName): string
    {
        return trim(ucwords(mb_strtolower($firstName)));
    }

    public function cleanLastName(string $lastName): string
    {
        return trim(ucwords(mb_strtolower($lastName)));
    }

    public function cleanEmail(string $email): string
    {
        return trim(mb_strtolower($email));
    }

    public function cleanPhoneNumber(string $phone): string
    {
        return mb_strtolower(trim($phone));
    }

    public function cleanNEPH(string $neph): string
    {
        return trim(mb_strtolower($neph));
    }

    public function isEmailValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
