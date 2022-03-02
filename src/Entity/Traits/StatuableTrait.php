<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait StatuableTrait
{
    /**
     * @ORM\Column(name="status", type="string")
     */
    protected ?string $status = null;

    // ------------------------------ >

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
