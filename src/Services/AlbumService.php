<?php

namespace App\Services;

use App\Entity\Album;
use App\Entity\User;
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

        var_dump("pouet");
        var_dump($travelId);
        $travel = $this->travelRepository->findOneBy(['id' => $travelId]);
        var_dump("pouet2");

        $album = (new Album())
            ->setTitle($title)
            ->setDescription($description)
            ->setTravelId($travel)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);

        $this->em->persist($album);
        $this->em->flush();

        return $album;
    }
}
