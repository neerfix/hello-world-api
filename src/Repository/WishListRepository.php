<?php

namespace App\Repository;

use App\Entity\WishList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WishList|null find($id, $lockMode = null, $lockVersion = null)
 * @method WishList|null findOneBy(array $criteria, array $orderBy = null)
 * @method WishList[]    findAll()
 * @method WishList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishList::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByUuid(string $uuid): ?WishList
    {
        return $this->createQueryBuilder('W')
            ->where('W.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @return WishList[]
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('W')
            ->where('W.status = :status')
            ->setParameter('status', 'active')

            ->getQuery()->getArrayResult();
    }

    // /**
    //  * @return WishList[] Returns an array of WishList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WishList
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
