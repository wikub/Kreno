<?php

namespace App\Repository;

use App\Entity\CommitmentLog;
use App\Entity\User;
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

    public function getSumnbTimeslot(User $user): ?int 
    {

        return $this->createQueryBuilder('cl')
            ->select('SUM(cl.nbTimeslot) as sumNbTimeslot')
            ->where('cl.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

    }

    public function getSumNbHour(User $user): ?int 
    {

        return $this->createQueryBuilder('cl')
            ->select('SUM(cl.nbHour) as sumNbHour')
            ->where('cl.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

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
