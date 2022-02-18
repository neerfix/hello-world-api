<?php

namespace App\Repository;

use App\Entity\AlbumFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AlbumFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumFile[]    findAll()
 * @method AlbumFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlbumFile::class);
    }

    // /**
    //  * @return AlbumFile[] Returns an array of AlbumFile objects
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
    public function findOneBySomeField($value): ?AlbumFile
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
