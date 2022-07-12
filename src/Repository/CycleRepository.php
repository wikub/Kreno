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

use App\Entity\Cycle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cycle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cycle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cycle[]    findAll()
 * @method Cycle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CycleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cycle::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Cycle $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Cycle $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findLast(): ?Cycle
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.start', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findCurrent(): ?Cycle
    {
        $date = (new \DateTime());

        return $this->findByStartDate($date);
    }

    public function findPrevious($week): ?Cycle
    {
        $date = $week->getStart()->modify('-4 weeks');

        return $this->findByStartDate($date);
    }

    public function findNext($week): ?Cycle
    {
        $date = $week->getStart()->modify('+4 weeks');

        return $this->findByStartDate($date);
    }

    public function findByStartDate($date): ?Cycle
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.start <= :date')
            ->andWhere('c.finish >= :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.start', 'ASC')
        ;
    }

    public function getLastOpen(): ?Cycle
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.applyCommimentContracts IS NULL')
            ->orderBy('c.start', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getLastClose(): ?Cycle
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.applyCommimentContracts IS NOT NULL')
            ->orderBy('c.start', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Cycle[] Returns an array of Cycle objects
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
    public function findOneBySomeField($value): ?Cycle
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
