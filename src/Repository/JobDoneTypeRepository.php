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

use App\Entity\JobDoneType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobDoneType|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobDoneType|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobDoneType[]    findAll()
 * @method JobDoneType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobDoneTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobDoneType::class);
    }

    public function findDefaultValue(): ?JobDoneType
    {
        return $this->createQueryBuilder('jbt')
            ->andWhere('jbt.position = 1')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return JobDoneType[] Returns an array of JobDoneType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JobDoneType
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
