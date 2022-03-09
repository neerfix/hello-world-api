<?php

namespace App\Services;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class PlaceService
{
    public function __construct(
        private EntityManagerInterface $em,
        private placeRepository $placeRepository
    ) {
    }

    public function create(string $address,
                           string $city,
                           string $zipcode,
                           string $country,
                           string $name,
                           float $latitude,
                           float $longitude): Place
    {
        $address = trim($address);
        $city = trim($city);
        $zipcode = trim($zipcode);
        $country = trim($country);
        $name = trim($name);

        $place = (new Place())
            ->setAddress($address)
            ->setZipcode($zipcode)
            ->setCity($city)
            ->setCountry($country)
            ->setName($name)
            ->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setUuid(Uuid::uuid4());

        $this->em->persist($place);
        $this->em->flush();

        return $place;
    }

    public function getAll() : array
    {
        return $this->placeRepository->findAll();
    }

    public function getByUuid(string $uuid): Place
    {
        return $this->placeRepository->findOneBy(['uuid' => $uuid]);
    }
}