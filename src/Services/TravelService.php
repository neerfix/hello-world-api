<?php

namespace App\Services;

use App\Entity\Travel;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class TravelService
{
    // ------------------------ >

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    // ------------------------ >

    public function create(
        string $name,
        float $budget,
        DateTime $startedAt,
        DateTime $endedAt,
        User $user,
        ?string $description = null,
        ?bool $isShared = false
    ): Travel {
        //TODO add check and verif data
        $travel = (new Travel())
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
            ->setName($name)
            ->setBudget($budget)
            ->setStartedAt($startedAt)
            ->setEndedAt($endedAt)
            ->setDescription($description)
            ->setUserId($user)
            ->setIsShared($isShared);

        $this->em->persist($travel);
        $this->em->flush();

        return $travel;
    }
}
