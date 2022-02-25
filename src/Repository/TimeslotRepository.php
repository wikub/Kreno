<?php

namespace App\Repository;

use App\Entity\Timeslot;
use App\Entity\TimeslotTemplate;
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

    public function findForValidation24h()
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.start <= :h24')
            ->setParameter('h24', (new \DateTime())->modify('+1 day'))
            ->andWhere('t.status LIKE \'%open%\'')
            ->orderBy('t.start', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findFutureByTimeslotTemplate(TimeslotTemplate $template, DateTime $start = null, Datetime $finish = null)
    {
        $now = new \DateTime();
        if( $start == null or $start <= $now) $start = $now;

        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.template = :template')
            ->andWhere('t.start >= :start')
            ->setParameter('template', $template)
            ->setParameter('start', $start);

            if( $finish != null ) {
                $qb->andWhere('t.start <= :finish')
                    ->setParameter('finish', $finish);
            }

        return $qb->getQuery()
            ->getResult();
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
