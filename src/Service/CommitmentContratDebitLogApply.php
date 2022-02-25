<?php

namespace App\Service;

use App\Entity\CommitmentContract;
use App\Entity\CommitmentLog;
use App\Repository\CommitmentContractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CommitmentContratDebitLogApply {

    private $em;
    private $repo;
    private $timeslotWorkflow;
    private $flash;
    private $commitmentContractRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        FlashBagInterface $flash,
        CommitmentContractRepository $commitmentContractRepository
    ) {
        $this->em = $entityManager;
        $this->flash = $flash;
        $this->commitmentContractRepository = $commitmentContractRepository;

    }

    public function applyDebit()
    {
        $contracts = $this->commitmentContractRepository->findCurrentContract();
        
        foreach($contracts as $contract) {
            
            $log = new CommitmentLog();
            $log->setUser($contract->getUser());
            $log->setNbTimeslot($contract->getType()->getNbTimeslotMin()*-1);
            $log->setNbHour($contract->getType()->getNbHourMin()*-1);
            $log->setComment('Debit cycle');

            $this->em->persist($log);
        }
        $this->em->flush();

    }
    
}