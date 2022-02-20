<?php

namespace App\Repository;

use App\Entity\Timeslot;
use App\Entity\Week;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Timeslot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timeslot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timeslot[]    findAll()
 * @method Timeslot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeslotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timeslot::class);
    }

    public function findByWeek(Week $week)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.week = :week')
            ->setParameter('week', $week)
            ->orderBy('t.start', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForValidation()
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.start <= :now')
            ->setParameter('now', (new \DateTime()))
            ->andWhere('t.status LIKE \'%open%\'')
            ->orderBy('t.start', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return Timeslot[] Returns an array of Timeslot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Timeslot
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
