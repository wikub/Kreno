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

use App\Entity\Week;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Week|null find($id, $lockMode = null, $lockVersion = null)
 * @method Week|null findOneBy(array $criteria, array $orderBy = null)
 * @method Week[]    findAll()
 * @method Week[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Week::class);
    }

    public function findAll()
    {
        return $this->findBy([], ['startAt' => 'ASC']);
    }

    public function findCurrent(): ?Week
    {
        $date = (new \DateTime())->modify('monday this week');

        return $this->findByStartDate($date);
    }

    public function findPrevious($week): ?Week
    {
        $date = $week->getStartAt()->modify('monday previous week');

        return $this->findByStartDate($date);
    }

    public function findNext($week): ?Week
    {
        $date = $week->getStartAt()->modify('monday next week');

        return $this->findByStartDate($date);
    }

    public function findByStartDate($date): ?Week
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.startAt = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Week[] Returns an array of Week objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Week
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
