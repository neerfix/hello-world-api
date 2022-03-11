<?php

namespace App\Services;

use App\Entity\Album;
use App\Entity\Place;
use App\Entity\Step;
use App\Entity\Travel;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ramsey\Uuid\Uuid;

class StepService
{
    // ------------------------ >

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    // ------------------------ >

    /**
     * @throws Exception
     */
    public function create(
        Travel $travel,
        Place $place,
        Album $album,
        ?DateTime $startedAt = null,
        ?DateTime $endedAt = null
    ): Step {
        $step = (new Step())
            ->setTravel($travel)
            ->setPlace($place)
            ->setAlbum($album)
            ->setStartedAt($startedAt)
            ->setEndedAt($endedAt)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
            ->setUuid(Uuid::uuid4());

        $this->em->persist($step);
        $this->em->flush();

        return $step;
    }

    /**
     * @throws Exception
     */
    public function delete(Step $step): Step
    {
        $step->setStatus(Step::STATUS_DELETED)
            ->setUpdatedAt(new DateTime());

        $this->em->persist($step);
        $this->em->flush();

        return $step;
    }

    public function update(
        Step $step,
        Album $album,
        Place $place,
        ?DateTime $startedAt = null,
        ?DateTime $endedAt = null
    ): Step {
        $step
            ->setAlbum($album)
            ->setPlace($place)
            ->setStartedAt($startedAt)
            ->setEndedAt($endedAt)
            ->setUpdatedAt(new DateTime());

        $this->em->persist($step);
        $this->em->flush();

        return $step;
    }
}
