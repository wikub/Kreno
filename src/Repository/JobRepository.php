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

use App\Entity\CommitmentContract;
use App\Entity\Job;
use App\Entity\TimeslotTemplate;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function findAllOrderByTimeslotStart(User $user)
    {
        return $this->createQueryBuilder('j')
            ->join('j.timeslot', 't')
            ->andWhere('j.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.start', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getFuturForRegular(TimeslotTemplate $template, User $user)
    {
        return $this->createQueryBuilder('j')
            ->join('j.timeslot', 't')
            // ->join('t.template', 'temp')
            // ->join('temp.regularCommitmentContracts', 'rg')
            // ->andwhere('rg.commitmentContract = :contract')
            ->andWhere('t.template = :template')
            ->andWhere('j.user = :user')
            ->andWhere('t.start >= :now')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTime())
            ->setParameter('template', $template)
                ->getQuery()
                ->getResult()
            ;

        // $qb = $this->createQueryBuilder('job');
            // return $qb->where($qb->expr()->In('job.id', $selectJob->getDQL() ))
            // //->setParameter('contract', $re)
            // ->setParameter('user', $regular->getCommitmentContract()->getUser())
            // ->setParameter('now', new \DateTime())
            // ->setParameter('regular', $regular)
            //     ->delete()
            //     ->getQuery()
            //     ->getResult()
            // ;
    }

    public function findNextForUser(User $user)
    {
        return $this->createQueryBuilder('j')
            ->join('j.timeslot', 't')
            ->andWhere('j.user = :user')
            ->setParameter('user', $user->getId())
            ->andWhere('t.start >= :dateNow')
            ->setParameter('dateNow', new \DateTime())
            ->andWhere('t.start <= :dateLimit')
            ->setParameter('dateLimit', (new \DateTime())->modify('+45 days'))
            ->orderBy('t.start', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Job[] Returns an array of Job objects
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
    public function findOneBySomeField($value): ?Job
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
