<?php

namespace App\Services;

use App\Entity\Place;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;

class PlaceService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function create(
        string $name,
        float $latitude,
        float $longitude,
        ?string $address = null,
        ?string $city = null,
        ?string $zipcode = null,
        ?string $country = null): Place
    {
        $name = trim($name);

        $place = (new Place())
            ->setAddress($address)
            ->setZipcode($zipcode)
            ->setCity($city)
            ->setCountry($country)
            ->setName($name)
            ->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setStatus(Place::STATUS_ACTIVE)
            ->setUuid(Uuid::uuid4());

        $this->em->persist($place);
        $this->em->flush();

        return $place;
    }

    public function update(
        Place $place,
        string $name,
        float $latitude,
        float $longitude,
        ?string $address = null,
        ?string $city = null,
        ?string $zipcode = null,
        ?string $country = null
    ): Place {
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
        if (Place::STATUS_DELETED === $place->getStatus()) {
            throw new RunTimeException('La localisation est déjà supprimée');
        }

        if (!in_array(User::ROLE_ADMIN, $user->getRoles(), true)) {
            throw new RuntimeException('Vous n\'avez pas l\'autorisation de supprimer cette localisation');
        }
        $place
            ->setStatus(Place::STATUS_DELETED)
            ->setUpdatedAt(new DateTime());

        $this->em->persist($place);
        $this->em->flush();

        return $place;
    }
}
