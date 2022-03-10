<?php

namespace App\Repository;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Token|null find($id, $lockMode = null, $lockVersion = null)
 * @method Token|null findOneBy(array $criteria, array $orderBy = null)
 * @method Token[]    findAll()
 * @method Token[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByValue(string $value): ?Token
    {
        return $this->createQueryBuilder('t')
            ->where('t.value = :value')
            ->setParameter('value', $value)

            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findRefreshTokenByUser(User $user): ?Token
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.target = :type')

            ->setParameter('user', $user)
            ->setParameter('type', Token::TYPE_REFRESH_TOKEN)

            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findAccessTokenByUser(User $user): ?Token
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.target = :type')

            ->setParameter('user', $user)
            ->setParameter('type', Token::TYPE_ACCESS_TOKEN)

            ->getQuery()
            ->getOneOrNullResult();
    }
}
