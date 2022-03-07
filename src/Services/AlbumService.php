<?php

namespace App\Services;

use App\Entity\Album;
use App\Repository\TravelRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class AlbumService
{
    // ------------------------ >

    public function __construct(
        private EntityManagerInterface $em,
        private TravelRepository $travelRepository
    ) {
    }

    // ------------------------ >

    /**
     * @throws Exception
     */
    public function create(
        string $title,
        string $description,
        int $travelId
    ): Album {
        $title = trim($title);
        $description = trim($description);

        $travel = $this->travelRepository->find($travelId);

        $album = (new Album())
            ->setTitle($title)
            ->setDescription($description)
            ->setTravelId($travel)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $this->em->persist($album);
        $this->em->flush();

        return $album;
    }
}
