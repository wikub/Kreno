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
use App\Entity\Cycle;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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

    public function findCurrentContract()
    {
        $finishCycle = new \DateTimeImmutable();
        $startCycle = $finishCycle->modify('-4 weeks');

        return $this->createQueryBuilder('cc')
            ->andWhere('cc.start >= :startCycle AND cc.start <=  :finishCycle')
            ->setParameter('startCycle', $startCycle)
            ->setParameter('finishCycle', $finishCycle)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCurrentContractForUser(User $user)
    {
        return $this->createQueryBuilder('cc')
            ->join('cc.startCycle', 'startCycle')
            ->leftJoin('cc.finishCycle', 'finishCycle')
            ->andWhere('startCycle.start <= :dateNow AND (finishCycle.finish IS NULL OR :dateNow <= finishCycle.finish)')
            ->setParameter('dateNow', new \DateTime())
            ->andWhere('cc.user = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @desc : find all open contracts for a cycle
     */
    public function findOpenForCycle(Cycle $cycle)
    {
        return $this->createQueryBuilder('cc')
            ->join('cc.startCycle', 'startCycle')
            ->leftJoin('cc.finishCycle', 'finishCycle')
            ->andWhere('startCycle.start <= :startCycle AND (finishCycle.finish IS NULL OR :startCycle <= finishCycle.finish) ')
            ->andWhere('startCycle.start <= :finishCycle AND (finishCycle.finish IS NULL OR :finishCycle <= finishCycle.finish) ')
            ->setParameter('startCycle', $cycle->getStart())
            ->setParameter('finishCycle', $cycle->getFinish())
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @desc : find all open contract between two cycles
     */
    public function findOpenForCommitmentContract(CommitmentContract $commitmentContract)
    {
        $startCycle = $commitmentContract->getStartCycle()->getStart();
        if (null === $commitmentContract->getFinishCycle()) {
            $finishCycle = null;
        } else {
            $finishCycle = $commitmentContract->getFinishCycle()->getFinish();
        }

        if (null !== $finishCycle && $startCycle > $finishCycle) {
            // throw new Exception('The start cycle is after the finish cycle');
            return new ArrayCollection();
        }

        $qb = $this->createQueryBuilder('cc')
            ->join('cc.startCycle', 'startCycle')
            ->leftJoin('cc.finishCycle', 'finishCycle')
            ->andWhere('startCycle.start <= :startCycle AND (finishCycle.finish IS NULL OR :startCycle <= finishCycle.finish) ')
            ->andWhere('startCycle.start <= :finishCycle AND (finishCycle.finish IS NULL OR :finishCycle <= finishCycle.finish) ')
            ->setParameter('startCycle', $startCycle)
            ->setParameter('finishCycle', $finishCycle);

        if ($commitmentContract->getId()) {
            $qb->andWhere('cc.id <> :id')
                ->setParameter('id', $commitmentContract->getId());
        }

        return $qb->getQuery()
            ->getResult();
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
