<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByUuid(string $uuid): ?User
    {
        return $this->createQueryBuilder('U')

            ->where('U.uuid = :uuid')
            ->setParameter('uuid', $uuid)

            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('U')

            ->where('U.email = :email')
            ->setParameter('email', $email)

            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByUsername(string $username): ?User
    {
        return $this->createQueryBuilder('U')

            ->where('U.username = :username')
            ->setParameter('username', $username)

            ->getQuery()->getOneOrNullResult();
    }
}
