<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByUuid(string $uuid): ?Album
    {
        return $this->createQueryBuilder('A')
            ->where('A.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Album[]
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('A')
            ->where('A.status = :status')
            ->setParameter('status', 'active')

            ->getQuery()->getArrayResult();
    }
}
