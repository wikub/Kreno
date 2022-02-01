<?php

namespace App\Repository;

use App\Entity\CommitmentContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommitmentContract|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommitmentContract|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommitmentContract[]    findAll()
 * @method CommitmentContract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommitmentContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommitmentContract::class);
    }

    // /**
    //  * @return CommitmentContract[] Returns an array of CommitmentContract objects
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
    public function findOneBySomeField($value): ?CommitmentContract
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
