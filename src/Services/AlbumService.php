<?php

namespace App\Services;

use App\Entity\Album;
use App\Entity\Travel;
use App\Repository\AlbumRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use RuntimeException;

class AlbumService
{
    // ------------------------ >

    private const DESCRIPTION_MINIMAL_WORD = 5;

    // ------------------------ >

    public function __construct(
        private EntityManagerInterface $em,
        private AlbumRepository $albumRepository
    ) {
    }

    // ------------------------ >

    /**
     * @throws Exception
     */
    public function create(
        string $title,
        ?string $description = null,
        Travel $travel
    ): Album {
        if (null !== $description) {
            preg_match_all('/([a-zA-Z][-\'0-9a-zÀ-ÿ]+)/m', $description, $words, PREG_SET_ORDER, 0);

            // Explanation is too short
            if (count($words) < static::DESCRIPTION_MINIMAL_WORD) {
                throw new RuntimeException(sprintf('La description ne peut pas être inférieure à %s mots', static::DESCRIPTION_MINIMAL_WORD));
            }
        }

        $title = trim($title);

        $album = (new Album())
            ->setTitle($title)
            ->setDescription($description)
            ->setTravel($travel)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
            ->setUuid(Uuid::uuid4());

        $this->em->persist($album);
        $this->em->flush();

        return $album;
    }

    /**
     * @throws Exception
     */
    public function getAll(): array
    {
        return $this->albumRepository->findAll();
    }

    /**
     * @throws Exception
     */
    public function getByUuid(string $uuid): ?Album
    {
        return $this->albumRepository->findOneByUuid($uuid);
    }

    /**
     * @throws Exception
     */
    public function delete(Album $album): Album
    {
        $this->em->remove($album);
        $this->em->flush();

        return $album;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function update(
        Album $album,
        string $title,
        string $description,
    ): Album {
        if (null !== $description) {
            preg_match_all('/([a-zA-Z][-\'0-9a-zÀ-ÿ]+)/m', $description, $words, PREG_SET_ORDER, 0);

            // Explanation is too short
            if (count($words) < static::DESCRIPTION_MINIMAL_WORD) {
                throw new RuntimeException(sprintf('La description ne peut pas être inférieure à %s mots', static::DESCRIPTION_MINIMAL_WORD));
            }
        }

        $title = trim($title);

        $album
            ->setTitle($title)
            ->setDescription($description)
            ->setUpdatedAt(new DateTime());

        $this->em->persist($album);
        $this->em->flush();

        return $album;
    }
}
