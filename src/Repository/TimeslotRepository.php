<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Timeslot;
use App\Entity\TimeslotTemplate;
use App\Entity\User;
use App\Entity\Week;
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

    public function findAllActive()
    {
        return $this->createQueryBuilder('t')
            ->join('t.jobs', 'j')
            ->leftJoin('j.user', 'user')
            ->andWhere('t.enabled = true')
            ->orderBy('t.start', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllActiveWhenUserSubscribe(User $user)
    {
        return $this->createQueryBuilder('t')
            ->join('t.jobs', 'j')
            ->join('j.user', 'user')
            ->andWhere('t.enabled = true')
            ->andWhere('j.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.start', 'ASC')
            ->getQuery()
            ->getResult()
        ;
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
            ->setParameter('now', new \DateTime())
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
            ->setParameter('h24', (new \DateTime())->modify('-1 day'))
            ->andWhere('t.status LIKE \'%open%\'')
            ->orderBy('t.start', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findFutureByTimeslotTemplate(TimeslotTemplate $template, \DateTimeImmutable $start = null, \DateTimeImmutable $finish = null)
    {
        $now = new \DateTime();
        if (null === $start || $start <= $now) {
            $start = $now;
        }

        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.template = :template')
            ->andWhere('t.start >= :start')
            ->setParameter('template', $template)
            ->setParameter('start', $start);

        if (null !== $finish) {
            $qb->andWhere('t.start <= :finish')
                    ->setParameter('finish', $finish);
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findSelection(array $timeslots)
    {
        return $this->createQueryBuilder('t')
        ->andWhere('t.id IN (:timeslotsSelection)')
        ->setParameter('timeslotsSelection', $timeslots)
        ->getQuery()
        ->getResult()
        ;
    }

    public function findUpcoming(int $startInHour, int $periodHour = 24)
    {
        return $this->createQueryBuilder('timeslot')
            ->join('timeslot.jobs', 'job')
            ->join('job.user', 'u')
            ->andWhere('timeslot.start BETWEEN :nDayStart AND :nDayEnd')
            ->setParameter('nDayStart', (new \DateTime())->modify('+'.$startInHour.' hours'))
            ->setParameter('nDayEnd', (new \DateTime())->modify('+'.($startInHour + $periodHour).' hours'))
            ->orderBy('timeslot.start', 'ASC')
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
