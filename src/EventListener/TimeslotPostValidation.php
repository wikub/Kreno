<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Entity\CommitmentLog;
use App\Entity\Timeslot;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use LogicException;
use Symfony\Component\Workflow\WorkflowInterface;

class TimeslotPostValidation
{
    private $timeslotWorkflow;
    private $em;

    public function __construct(WorkflowInterface $timeslotWorkflow, EntityManagerInterface $entityManager)
    {
        $this->timeslotWorkflow = $timeslotWorkflow;
        $this->em = $entityManager;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postUpdate(Timeslot $timeslot, LifecycleEventArgs $event): void
    {
        if (!$timeslot->isValidated()) {
            return;
        }

        foreach ($timeslot->getJobs() as $job) {
            if (null === $job->getUser()) {
                continue;
            }

            // add commitmentlog credit
            if ($job->getJobDone()->getCommitmentCalculation()) {
                $commitLog = new CommitmentLog();
                $commitLog->setUser($job->getUser());
                $commitLog->setNbTimeslot(1);
                $commitLog->setComment($timeslot->getDisplayName().' '.$timeslot->getDisplayDateInterval());
                $this->em->persist($commitLog);
            }
        }

        try {
            $this->timeslotWorkflow->apply($timeslot, 'to_commitment_log');
        } catch (LogicException $exception) {
            return;
        }

        $this->em->persist($timeslot);
        $this->em->flush();
    }
}
