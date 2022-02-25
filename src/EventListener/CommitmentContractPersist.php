<?php

namespace App\EventListener;

use App\Entity\CommitmentContract;
use App\Entity\CommitmentContractRegularTimeslot;
use App\Repository\JobRepository;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

class CommitmentContractPersist {


    private $em;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->em = $entityManager;
    }

    public function postUpdate(CommitmentContract $contract, LifecycleEventArgs $event): void
    {
        foreach($contract->getRegularTimeslots() as $regular) {
            $regular->setStart($contract->getStart());
            $regular->setFinish($contract->getFinish());
            $this->em->persist($regular);
        }
        $this->em->flush();
    }

    public function postPersist(CommitmentContract $contract, LifecycleEventArgs $event): void
    {
    
    }

    
}