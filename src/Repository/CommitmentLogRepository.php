<?php

namespace App\Repository;

use App\Entity\CommitmentLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommitmentLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommitmentLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommitmentLog[]    findAll()
 * @method CommitmentLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommitmentLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommitmentLog::class);
    }

    // /**
    //  * @return CommitmentLog[] Returns an array of CommitmentLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommitmentLog
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
