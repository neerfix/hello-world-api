<?php

namespace App\Services;

use App\Entity\Place;
use App\Entity\User;
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

    public function getByUuid(string $uuid): ?Place
    {
        return $this->placeRepository->findOneBy(['uuid' => $uuid]);
    }

    public function update(
        Place $place,
        User $user,
        string $address,
        string $city,
        string $zipcode,
        string $country,
        string $name,
        float $latitude,
        float $longitude
    ): Place {
        $address = trim($address);
        $city = trim($city);
        $zipcode = trim($zipcode);
        $country = trim($country);
        $name = trim($name);

        $place
            ->setAddress($address)
            ->setZipcode($zipcode)
            ->setCountry($country)
            ->setCity($city)
            ->setName($name)
            ->setLatitude($latitude)
            ->setLongitude($longitude);

        $this->em->persist($place);
        $this->em->flush();

        return $place;
    }

    /**
     * @throws Exception
     */
    public function delete(
        Place $place,
        User $user
    ): Place {
        return $place;
    }
}
