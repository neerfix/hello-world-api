<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\TransactionRequiredException;
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
     * Find a user from its email.
     *
     * @throws NonUniqueResultException
     * @throws TransactionRequiredException
     */
    public function findOneByEmail(string $email, ?int $lockMode = null): ?User
    {
        return $this->_findOneBy('email', $email, $lockMode);
    }

    // ------------------------------ >

    /**
     * Find a user by a field and value.
     *
     * @throws NonUniqueResultException
     * @throws TransactionRequiredException
     */
    private function _findOneBy(string $field, mixed $value, ?int $lockMode = null): ?User
    {
        $query = $this
            ->createQueryBuilder('U')

            ->where("U.{$field} = :value")
            ->setParameter('value', $value)

            ->getQuery();

        if ((null !== $lockMode) && $this->getEntityManager()->getConnection()->isTransactionActive()) {
            $query->setLockMode($lockMode);
        }

        return $query->getOneOrNullResult();
    }
}
