<?php

namespace App\Repository;

use App\Entity\UserReponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserReponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserReponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserReponse[]    findAll()
 * @method UserReponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserReponse::class);
    }

    // /**
    //  * @return UserReponse[] Returns an array of UserReponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserReponse
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
