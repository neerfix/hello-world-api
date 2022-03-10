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
        return $this->_em->createQueryBuilder()
            ->select('u')
            ->from('App\Entity\User', 'u')
            ->where('u.email = :email')
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

    public function search(string $q, ?string $status = User::STATUS_ACTIVE): array
    {
        $query = $this->createQueryBuilder('U')

            ->where('U.username LIKE :query or U.firstname LIKE :query OR U.lastname LIKE :query')
            ->andWhere('U.status = :status')

            ->setParameter('query', '%'.$q.'%')
            ->setParameter('status', $status);

        return $query->getQuery()->getResult();
    }

    /**
     * @return User[]
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('U')
            ->where('U.status = :status')
            ->setParameter('status', 'active')

            ->getQuery()->getArrayResult();
    }
}
