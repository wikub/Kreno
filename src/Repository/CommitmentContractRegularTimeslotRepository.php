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

use App\Entity\CommitmentContractRegularTimeslot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommitmentContractTimeslotTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommitmentContractTimeslotTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommitmentContractTimeslotTemplate[]    findAll()
 * @method CommitmentContractTimeslotTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommitmentContractRegularTimeslotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommitmentContractRegularTimeslot::class);
    }

    // /**
    //  * @return CommitmentContractTimeslotTemplate[] Returns an array of CommitmentContractTimeslotTemplate objects
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
    public function findOneBySomeField($value): ?CommitmentContractTimeslotTemplate
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
