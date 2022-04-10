<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\CommitmentLog;
use App\Entity\Cycle;
use App\Repository\CommitmentContractRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommitmentContratDebitLogApply
{
    private $em;
    private $commitmentContractRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CommitmentContractRepository $commitmentContractRepository
    ) {
        $this->em = $entityManager;
        $this->commitmentContractRepository = $commitmentContractRepository;
    }

    // public function applyDebit()
    // {
    //     $contracts = $this->commitmentContractRepository->findCurrentContract();

    //     foreach ($contracts as $contract) {
    //         $log = new CommitmentLog();
    //         $log->setUser($contract->getUser());
    //         $log->setNbTimeslot($contract->getType()->getNbTimeslotMin() * -1);
    //         $log->setNbHour($contract->getType()->getNbHourMin() * -1);
    //         $log->setComment('Debit cycle');

    //         $this->em->persist($log);
    //     }
    //     $this->em->flush();
    // }

    public function applyCommitmentContracts(Cycle $cycle)
    {
        $contracts = $this->commitmentContractRepository->findOpenForCycle($cycle);

        foreach ($contracts as $contract) {
            $log = new CommitmentLog();
            $log->setUser($contract->getUser());
            $log->setNbTimeslot($contract->getType()->getNbTimeslotMin() * -1);
            $log->setNbHour($contract->getType()->getNbHourMin() * -1);
            $log->setComment('Application engagements du cycle '.$cycle->getStart()->format('d/m/Y').' au '.$cycle->getFinish()->format('d/m/Y').'.');

            $this->em->persist($log);
        }

        $cycle->setApplyCommimentContracts(new \DateTime());
        $this->em->persist($cycle);

        $this->em->flush();
    }
}
