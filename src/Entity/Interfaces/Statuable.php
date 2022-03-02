<?php

namespace App\Entity\Interfaces;

interface Statuable
{
    public function getStatus(): string;

    public function setStatus(string $status);
}
