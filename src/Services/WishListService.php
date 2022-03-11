<?php

namespace App\Services;

use App\Entity\Place;
use App\Entity\User;
use App\Entity\WishList;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Ramsey\Uuid\Uuid;
use RuntimeException;

class WishListService
{
    // ------------------------ >

    private const DESCRIPTION_MINIMAL_WORD = 5;

    // ------------------------ >

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    // ------------------------ >

    /**
     * @throws Exception
     */
    public function create(
        string $name,
        Place $place,
        User $user,
        ?string $description = null,
        ?DateTime $estimatedAt = null
    ): WishList {
        if (null !== $description) {
            preg_match_all('/([a-zA-Z][-\'0-9a-zÀ-ÿ]+)/m', $description, $words, PREG_SET_ORDER, 0);

            // Explanation is too short
            if (count($words) < static::DESCRIPTION_MINIMAL_WORD) {
                throw new RuntimeException(sprintf('La description ne peut pas être inférieure à %s mots', static::DESCRIPTION_MINIMAL_WORD));
            }
        }

        $name = trim($name);

        $wishList = (new WishList())
            ->setName($name)
            ->setDescription($description)
            ->setPlace($place)
            ->setUser($user)
            ->setEstimatedAt($estimatedAt)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
            ->setUuid(Uuid::uuid4())
            ->setStatus(WishList::STATUS_ACTIVE);

        $this->em->persist($wishList);
        $this->em->flush();

        return $wishList;
    }

    /**
     * @throws Exception
     */
    public function delete(WishList $wishList): WishList
    {
        $wishList
            ->setStatus(WishList::STATUS_DELETED)
            ->setUpdatedAt(new DateTime());

        $this->em->persist($wishList);
        $this->em->flush();

        return $wishList;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function update(
        WishList $wishList,
        string $name,
        Place $place,
        User $user,
        ?string $description = null,
        ?DateTime $estimatedAt = null
    ): WishList {
        if (null !== $description) {
            preg_match_all('/([a-zA-Z][-\'0-9a-zÀ-ÿ]+)/m', $description, $words, PREG_SET_ORDER, 0);

            // Explanation is too short
            if (count($words) < static::DESCRIPTION_MINIMAL_WORD) {
                throw new RuntimeException(sprintf('La description ne peut pas être inférieure à %s mots', static::DESCRIPTION_MINIMAL_WORD));
            }
        }

        $name = trim($name);

        $wishList
            ->setName($name)
            ->setDescription($description)
            ->setPlace($place)
            ->setUser($user)
            ->setEstimatedAt($estimatedAt)
            ->setUpdatedAt(new DateTime());

        $this->em->persist($wishList);
        $this->em->flush();

        return $wishList;
    }
}
