<?php

namespace App\Services;

use App\Entity\Album;
use App\Entity\Place;
use App\Entity\Step;
use App\Entity\Travel;
use App\Repository\StepRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ramsey\Uuid\Uuid;

class StepService
{
    // ------------------------ >

    private const DESCRIPTION_MINIMAL_WORD = 5;

    // ------------------------ >

    public function __construct(
        private EntityManagerInterface $em,
        private StepRepository $stepRepository
    ) {
    }

    // ------------------------ >

    /**
     * @throws Exception
     */
    public function create(
        Travel $travel,
        Place $place,
        ?DateTime $startedAt = null,
        ?DateTime $endedAt = null
    ): Step {
        $step = (new Step())
            ->setTravel($travel)
            ->setPlace($place)
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
    public function getAll(): array
    {
        return $this->stepRepository->findAll();
    }

    /**
     * @throws Exception
     */
    public function getByUuid(string $uuid): ?Step
    {
        return $this->stepRepository->findOneByUuid($uuid);
    }

    /**
     * @throws Exception
     */
    public function delete(Step $step): Step
    {
        $this->em->remove($step);
        $this->em->flush();

        return $step;
    }

    /**
     * @throws NonUniqueResultException
     */
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
